<?php
	class JsonProcessing {
        public $arr_products = array (
            "name" => [],
            "price" => []
        );

        public function getArrayProducts() {
            return $this->arr_products;
        }

        // Este método que procesa el Json e inserta sus valores en un array.
        public function dumpJson($data) {
            foreach ($data as $key => $value) {
                if (is_array($value) || is_object($value)) {
                    $this->dumpJson($value);
                } else {
                    array_push($this->arr_products['name'],$data['name']);
                    array_push($this->arr_products['price'],$data['price']);
                    return $this->arr_products;
                }
            }
        }
    }
?>