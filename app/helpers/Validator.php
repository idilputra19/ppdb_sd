<?php
class Validator {
    private $errors = [];

    public function validate($data, $rules) {
        foreach ($rules as $field => $rule_list) {
            $value = $data[$field] ?? null;
            $rules = explode('|', $rule_list);
            
            foreach ($rules as $rule) {
                if (strpos($rule, ':') !== false) {
                    list($rule_name, $rule_value) = explode(':', $rule);
                } else {
                    $rule_name = $rule;
                    $rule_value = null;
                }
                
                $method = 'validate' . ucfirst($rule_name);
                if (method_exists($this, $method)) {
                    if (!$this->$method($field, $value, $rule_value)) {
                        break;
                    }
                }
            }
        }
        
        return empty($this->errors);
    }

    public function getErrors() {
        return $this->errors;
    }

    private function validateRequired($field, $value) {
        if (empty($value)) {
            $this->errors[$field] = ucfirst($field) . ' harus diisi';
            return false;
        }
        return true;
    }

    private function validateEmail($field, $value) {
        if (!empty($value) && !filter_var($value, FILTER_VALIDATE_EMAIL)) {
            $this->errors[$field] = 'Format email tidak valid';
            return false;
        }
        return true;
    }

    private function validateMin($field, $value, $min) {
        if (!empty($value) && strlen($value) < $min) {
            $this->errors[$field] = ucfirst($field) . ' minimal ' . $min . ' karakter';
            return false;
        }
        return true;
    }

    private function validateNumeric($field, $value) {
        if (!empty($value) && !is_numeric($value)) {
            $this->errors[$field] = ucfirst($field) . ' harus berupa angka';
            return false;
        }
        return true;
    }

    private function validateDate($field, $value) {
        if (!empty($value)) {
            $date = date_parse($value);
            if ($date['error_count'] > 0) {
                $this->errors[$field] = 'Format tanggal tidak valid';
                return false;
            }
        }
        return true;
    }

    private function validateFile($field, $file, $allowed_types) {
        if (empty($file['tmp_name'])) {
            $this->errors[$field] = 'File harus diupload';
            return false;
        }

        $file_type = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
        $allowed = explode(',', $allowed_types);
        
        if (!in_array($file_type, $allowed)) {
            $this->errors[$field] = 'Tipe file tidak diizinkan';
            return false;
        }
        
        return true;
    }
}