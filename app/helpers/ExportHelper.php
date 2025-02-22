<?php
class ExportHelper {
    public static function toExcel($data, $filename) {
        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment;filename="'. $filename .'.xls"');
        header('Cache-Control: max-age=0');
        
        $output = '';
        
        // Header
        if (!empty($data)) {
            $output .= implode("\t", array_keys($data[0])) . "\n";
        }
        
        // Data
        foreach ($data as $row) {
            $row = array_map(function($value) {
                return str_replace('"', '""', $value);
            }, $row);
            $output .= implode("\t", $row) . "\n";
        }
        
        return $output;
    }

    public static function toPDF($data, $title) {
        require_once '../vendor/autoload.php';
        
        $pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
        
        $pdf->SetCreator('PPDB System');
        $pdf->SetAuthor('Admin PPDB');
        $pdf->SetTitle($title);
        
        $pdf->AddPage();
        
        // Add content
        $html = '<h1>'.$title.'</h1>';
        $html .= '<table border="1">';
        
        // Header
        if (!empty($data)) {
            $html .= '<tr>';
            foreach (array_keys($data[0]) as $header) {
                $html .= '<th>'.htmlspecialchars($header).'</th>';
            }
            $html .= '</tr>';
        }
        
        // Data
        foreach ($data as $row) {
            $html .= '<tr>';
            foreach ($row as $value) {
                $html .= '<td>'.htmlspecialchars($value).'</td>';
            }
            $html .= '</tr>';
        }
        
        $html .= '</table>';
        
        $pdf->writeHTML($html, true, false, true, false, '');
        
        return $pdf->Output($title.'.pdf', 'D');
    }
}