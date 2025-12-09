<?php

class ProductNormalizer_model  extends MY_Model
{
    public $product_table = 'products'; 
    public function __construct()
    {
        parent::__construct();

    }
    private $optionableColumns = [
        'color'                  => 'Color',
        'size'                   => 'Size',
        'weight'                 => 'Weight',
        'quantity_per_box'       =>'quantity_per_box',
        'flavor'                 => 'Flavor',
        'shade'                  => 'Shade',
        'grit'                   => 'Grit',
        'viscosity'              => 'Viscosity',
        'firmness'               => 'Firmness',
        'handle_size'            => 'Handle Size',
        'handle_finish'          => 'Handle Finish',
        'tip_finish'             => 'Tip Finish',
        'tip_diameter'           => 'Tip Diameter',
        'tip_material'           => 'Tip Material',
        'head_diameter'          => 'Head Diameter',
        'head_length'            => 'Head Length',
        'diameter'               => 'Diameter',
        'shaft_dimensions'       => 'Shaft Dimensions',
        'shaft_description'      => 'Shaft Description',
        'blade_description'      => 'Blade Description',
        'anatomic_use'           => 'Anatomic Use',
        'instrument_description' => 'Instrument Description',
        'palm_thickness'         => 'Palm Thickness',
        'finger_thickness'       => 'Finger Thickness',
        'texture'                => 'Texture',
        'delivery_system'        => 'Delivery System',
        'dimensions'             => 'Dimensions',
        'stone_type'             => 'Stone Type',
        'stone_separation_time'  => 'Stone Separation Time',
        'setting_time'           => 'Setting Time',
        'band_thickness'         => 'Band Thickness',
        // add/remove as needed
    ];

    private $optionCache = [];     // key: product_id|option_name => option_id
    private $valueCache  = [];     // key: option_id|value => value_id



public function getProductsWithOptions($chunkSize = 50)
{
    $offset = 0;
    $productsWithOptions = [];

        $rows = $this->db->select('id, matix_id, name, ' . implode(',', array_keys($this->optionableColumns)))
            ->from('products')
            ->limit($chunkSize, $offset)
            ->get()
            ->result_array();


        // Loop through each product
        foreach ($rows as $row) {
            $productId = $row['matix_id']; // Assuming products.id == products.id
            if (!$productId) continue;

            // Step 1: Collect non-empty attributes
            $attrs = [];
            foreach ($this->optionableColumns as $col => $label) {
                if (!array_key_exists($col, $row)) continue;

                $val = trim((string)$row[$col]);
               if ($val === '' || $val ==='0') continue;

                if ($col === 'weight') {
                    // Process weight attribute
                    $val = preg_replace('/[^0-9.]+/', '', $val);
                    $val = ltrim($val, '.'); // Avoid leading dot
                    if ($val === '' || $val ==='0') continue; // If nothing left, skip
                }

                $attrs[$col] = $val;
            }

            // Skip products with no valid options (attributes)
            if (empty($attrs)) continue;

            // Add the product and its options to the result array
            $productsWithOptions[] = [
                'product_id' => $productId,
                'name'       => $row['name'],
                'options'    => $attrs // Non-empty attributes (options)
            ];
        }

        // Increase the offset for the next chunk
        $offset += $chunkSize;   

    return $productsWithOptions;

}




public function normalize_existing_products($chunkSize = 5)
{
    set_time_limit(0);
    $this->db->trans_start();
    
    $summary = [
        'products_processed' => 0,
        'skus_created' => 0,
        'errors' => []
    ];
    
    $offset = 0;
    
    // while (true) {
        // Fetch products in chunks
        $products = $this->db->select('id, matix_id, name, ' . implode(',', array_keys($this->optionableColumns)))
                            ->from('products')
                            ->limit($chunkSize, $offset)
                            ->get()
                            ->result_array();
        
        // if (empty($products)) break;
        
        foreach ($products as $product) {
            try {
                $productId = $product['matix_id'];
                $productDbId = $product['id'];
                
                if (!$productId) continue;
                
                // Step 1: Extract options from product attributes
                $extractedOptions = $this->extract_options_from_product($product);
                
                if (empty($extractedOptions)) {
                    continue; // No options to process
                }
        //         echo"<pre>";
        // print_r($extractedOptions);die();
        // echo"</pre>";
                $summary['products_processed']++;
                
                // Step 2: Insert options and option values
                $optionValueMap = $this->process_product_options($productId, $extractedOptions, $summary);
                
                // Step 3: Generate SKUs from the option combinations
                $this->generate_skus_for_product($productId, $productDbId, $optionValueMap, $summary);
                
            } catch (Exception $e) {
                $summary['errors'][] = "Product {$productId}: " . $e->getMessage();
            }
        }
        
        $offset += $chunkSize;

    // }while end
    
    $this->db->trans_complete();
    
    if ($this->db->trans_status() === FALSE) {
        $summary['status'] = 'error';
        $summary['message'] = 'Database transaction failed';
    } else {
        $summary['status'] = 'success';
        $summary['message'] = 'Normalization completed successfully';
    }
    
    return $summary;
}

private function extract_options_from_product($product)
{
    $options = [];
    
    foreach ($this->optionableColumns as $column => $optionName) {
        if (!isset($product[$column]) || empty($product[$column])) {
            continue;
        }
        
        $value = trim($product[$column]);
        
        // Special handling for specific column types
        if ($column === 'weight') {
            $value = preg_replace('/[^0-9.]+/', '', $value);
            $value = ltrim($value, '.');
            if ($value === '' || $value === '0') {
                continue;
            }
        }
        
        $options[$optionName] = $value;
    }
    
    return $options;
}

private function process_product_options($productId, $options, &$summary)
{
    $optionValueMap = []; // Maps option_type => [value_id, option_code]
    
    foreach ($options as $optionType => $optionValue) {
        // Get or create option
        $optionId = $this->get_or_create_option($productId, $optionType, $optionValue, $summary);
        
        // Get or create option value
        $valueId = $this->get_or_create_option_value($productId, $optionId, $optionValue, $summary);
        
        $optionValueMap[$optionType] = [
            'value_id' => $valueId,
            'option_code' => $this->generate_option_code($optionValue)
        ];
    }
    
    return $optionValueMap;
}

private function get_or_create_option($productId, $optionType, $optionValue, &$summary)
{
    // Check if option already exists (globally by type, not per product)
    $optionCode=$this->generate_option_code($optionValue);
    $existingOption = $this->db->select('option_id')
                              ->from('product_options')
                              ->where('option_type', $optionType)
                              ->where('option_code', $optionCode)
                              ->get()
                              ->row();
    
    if ($existingOption) {
        return $existingOption->option_id;
    }
    
    // Create new option
    $optionCode = $this->generate_option_code($optionValue);
    
    $this->db->insert('product_options', [
        'option_type' => $optionType,
        'option_code' => $optionCode,
    ]);
    
    $optionId = $this->db->insert_id();
    $summary['options_created']++;
    
    return $optionId;
}

private function get_or_create_option_value($productId, $optionId, $optionValue, &$summary)
{
    // Check if option value already exists
    $existingValue = $this->db->select('value_id')
                             ->from('product_option_values')
                             ->where('option_id', $optionId)
                             ->where('value', $optionValue)
                             ->get()
                             ->row();
    
    if ($existingValue) {
        // Update product_id if it's different
        if ($existingValue->product_id != $productId) {
            $this->db->where('value_id', $existingValue->value_id)
                    ->update('product_option_values', ['product_id' => $productId]);
        }
        return $existingValue->value_id;
    }
    
    // Create new option value
    $this->db->insert('product_option_values', [
        'option_id' => $optionId,
        'product_id' => $productId,
        'value' => $optionValue,
        'created_at' => date('Y-m-d H:i:s')
    ]);
    
    $valueId = $this->db->insert_id();
    $summary['option_values_created']++;
    
    return $valueId;
}

private function generate_skus_for_product($productId, $productDbId, $optionValueMap, &$summary)
{
    if (empty($optionValueMap)) {
        return;
    }
    
    // Create a single SKU for the base product with all options
    $optionCodes = array_column($optionValueMap, 'option_code');
    $valueIds = array_column($optionValueMap, 'value_id');
    
    $skuCode = $productId . '-' . implode('-', $optionCodes);
    
    // Check if SKU already exists
    $existingSku = $this->db->select('sku_id')
                           ->from('skus')
                           ->where('sku_code', $skuCode)
                           ->get()
                           ->row();
    
    if ($existingSku) {
        return $existingSku->sku_id;
    }
    
    // Create new SKU
    $this->db->insert('skus', [
        'product_id' => $productId,
        'sku_code' => $skuCode,
        'price' => null,
        'retail_price' => null,
        'stock_quantity' => null,
        'status' => 'active',
        'exclude_from_whitelabels_1' => 0,
        'exclude_from_whitelabels_2' => 0,
        'exclude_from_marketplace' => 0,
        'minimum_threshold' => null,
        'created_at' => date('Y-m-d H:i:s')
    ]);
    
    $skuId = $this->db->insert_id();
    $summary['skus_created']++;
    
    // Link SKU to option values
    foreach ($valueIds as $valueId) {
        $this->db->insert('sku_option_values', [
            'sku_id' => $skuId,
            'value_id' => $valueId,
        ]);
    }
    
    // Also update product_pricings table
    $product = $this->db->select('mpn, matix_id')
                       ->where('id', $productDbId)
                       ->get($this->product_table)
                       ->row();
    
    if ($product) {
        $this->db->insert('product_pricings', [
            'product_id' => $productDbId,
            'sku' => $skuCode,
            'vendor_product_id' => $product->mpn,
            'matix_id' => $product->matix_id,
            'minimum_threshold' => 0,
            'vendor_id' => 8, // You might want to make this configurable
            'price' => null,
            'retail_price' => null,
            'active' => 1,
            'quantity' => null,
            'exclude_from_marketplace' => 0,
            'exclude_from_whitelabels_1' => 0,
            'exclude_from_whitelabels_2' => 0,
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s'),
        ]);
    }
    
    return $skuId;
}

private function generate_option_code($value)
{
    // Simple option code generation - you can customize this
    return strtoupper(preg_replace('/[^a-zA-Z0-9]/', '', substr($value, 0, 10)));
}

// Helper method to get products with options (for verification)
public function get_products_with_options($limit = 10)
{
    $this->db->select('p.matix_id as product_id, p.name, po.option_type, pov.value')
            ->from($this->product_table . ' p')
            ->join('product_option_values pov', 'p.matix_id = pov.product_id', 'left')
            ->join('product_options po', 'pov.option_id = po.option_id', 'left')
            ->where('pov.value IS NOT NULL')
            ->limit($limit);
    
    $result = $this->db->get()->result_array();
    
    $products = [];
    foreach ($result as $row) {
        $productId = $row['product_id'];
        if (!isset($products[$productId])) {
            $products[$productId] = [
                'product_id' => $productId,
                'name' => $row['name'],
                'options' => []
            ];
        }
        
        if ($row['option_type']) {
            $products[$productId]['options'][$row['option_type']] = $row['value'];
        }
    }
    
    return array_values($products);
}



}
