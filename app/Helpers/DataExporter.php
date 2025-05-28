<?php

namespace App\Helpers;

use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Storage;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Writer\Csv;

class DataExporter
{
    /**
     * Export data to CSV
     *
     * @param array|Collection $data
     * @param array $headers
     * @param string $filename
     * @param string $disk
     * @return string
     */
    public static function toCsv($data, array $headers, string $filename, string $disk = 'local'): string
    {
        $data = $data instanceof Collection ? $data->toArray() : $data;
        
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        // Add headers
        $column = 1;
        foreach ($headers as $header) {
            $sheet->setCellValueByColumnAndRow($column++, 1, $header);
        }

        // Add data
        $row = 2;
        foreach ($data as $item) {
            $column = 1;
            foreach ($headers as $key => $header) {
                $value = is_string($key) ? $item[$key] : $item[$header];
                $sheet->setCellValueByColumnAndRow($column++, $row, $value);
            }
            $row++;
        }

        $writer = new Csv($spreadsheet);
        $path = 'exports/' . $filename;
        
        Storage::disk($disk)->makeDirectory('exports');
        $fullPath = Storage::disk($disk)->path($path);
        
        $writer->save($fullPath);

        return $path;
    }

    /**
     * Export data to Excel
     *
     * @param array|Collection $data
     * @param array $headers
     * @param string $filename
     * @param string $disk
     * @return string
     */
    public static function toExcel($data, array $headers, string $filename, string $disk = 'local'): string
    {
        $data = $data instanceof Collection ? $data->toArray() : $data;
        
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        // Add headers
        $column = 1;
        foreach ($headers as $header) {
            $sheet->setCellValueByColumnAndRow($column++, 1, $header);
        }

        // Add data
        $row = 2;
        foreach ($data as $item) {
            $column = 1;
            foreach ($headers as $key => $header) {
                $value = is_string($key) ? $item[$key] : $item[$header];
                $sheet->setCellValueByColumnAndRow($column++, $row, $value);
            }
            $row++;
        }

        $writer = new Xlsx($spreadsheet);
        $path = 'exports/' . $filename;
        
        Storage::disk($disk)->makeDirectory('exports');
        $fullPath = Storage::disk($disk)->path($path);
        
        $writer->save($fullPath);

        return $path;
    }

    /**
     * Export data to JSON
     *
     * @param array|Collection $data
     * @param string $filename
     * @param string $disk
     * @param int $options
     * @return string
     */
    public static function toJson($data, string $filename, string $disk = 'local', int $options = JSON_PRETTY_PRINT): string
    {
        $data = $data instanceof Collection ? $data->toArray() : $data;
        $path = 'exports/' . $filename;
        
        Storage::disk($disk)->makeDirectory('exports');
        Storage::disk($disk)->put($path, json_encode($data, $options));

        return $path;
    }

    /**
     * Export data to XML
     *
     * @param array|Collection $data
     * @param string $rootElement
     * @param string $itemElement
     * @param string $filename
     * @param string $disk
     * @return string
     */
    public static function toXml($data, string $rootElement, string $itemElement, string $filename, string $disk = 'local'): string
    {
        $data = $data instanceof Collection ? $data->toArray() : $data;
        
        $xml = new \SimpleXMLElement("<?xml version=\"1.0\" encoding=\"UTF-8\"?><{$rootElement}></{$rootElement}>");
        
        $arrayToXml = function($array, $xml) use (&$arrayToXml, $itemElement) {
            foreach ($array as $key => $value) {
                if (is_array($value)) {
                    if (is_numeric($key)) {
                        $key = $itemElement;
                    }
                    $subnode = $xml->addChild($key);
                    $arrayToXml($value, $subnode);
                } else {
                    if (is_numeric($key)) {
                        $key = $itemElement;
                    }
                    $xml->addChild($key, htmlspecialchars($value));
                }
            }
        };

        $arrayToXml($data, $xml);
        
        $path = 'exports/' . $filename;
        
        Storage::disk($disk)->makeDirectory('exports');
        Storage::disk($disk)->put($path, $xml->asXML());

        return $path;
    }

    /**
     * Export data to PDF
     *
     * @param string $html
     * @param string $filename
     * @param string $disk
     * @param string $orientation portrait|landscape
     * @return string
     */
    public static function toPdf(string $html, string $filename, string $disk = 'local', string $orientation = 'portrait'): string
    {
        $pdf = new \Dompdf\Dompdf();
        $pdf->setPaper('A4', $orientation);
        $pdf->loadHtml($html);
        $pdf->render();

        $path = 'exports/' . $filename;
        
        Storage::disk($disk)->makeDirectory('exports');
        Storage::disk($disk)->put($path, $pdf->output());

        return $path;
    }

    /**
     * Get supported export formats
     *
     * @return array
     */
    public static function getSupportedFormats(): array
    {
        return [
            'csv' => 'CSV (Comma Separated Values)',
            'xlsx' => 'Excel Workbook',
            'json' => 'JSON (JavaScript Object Notation)',
            'xml' => 'XML (Extensible Markup Language)',
            'pdf' => 'PDF (Portable Document Format)'
        ];
    }
} 