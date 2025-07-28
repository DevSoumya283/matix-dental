<?php


class Stocks_Model extends CI_Model {

    public function get_stock_by_product($product_id) {
        $this->db->select('stocks');
        $this->db->from('stocks');
        $this->db->where('products_id', $product_id);
        $query = $this->db->get();

        if ($query->num_rows() > 0) {
            return (int) $query->row()->stocks;
        } else {
            return 0; // return 0 if not found
        }
    }
}
