<?php
class Productdata_model extends CI_Model {

    public function save_excel_rows($rows) {
        foreach ($rows as $row) {
            $this->db->insert('products', $row);
        }
    }

    public function get_all_products() {
        return $this->db->get('products')->result();
    }

    public function export_all_products() {
        $products = $this->get_all_products();
        header("Content-Type: application/vnd.ms-excel");
        header("Content-Disposition: attachment; filename=products_all.xls");
        $this->load->view('product_system/export', ['products' => $products]);
    }

    public function export_single_product($id) {
        $this->db->where('id', $id);
        $product = $this->db->get('products')->row_array();
        header("Content-Type: application/vnd.ms-excel");
        header("Content-Disposition: attachment; filename=product_{$id}.xls");
        $this->load->view('product_system/export', ['products' => [$product]]);
    }

    public function add_options($product_id, $columns) {
        foreach ($columns as $col) {
            $this->db->insert('product_options', [
                'product_id' => $product_id,
                'option_name' => trim($col)
            ]);
        }
    }

    public function get_product_by_id($id) {
        return $this->db->get_where('products', ['id' => $id])->row_array();
    }


}
