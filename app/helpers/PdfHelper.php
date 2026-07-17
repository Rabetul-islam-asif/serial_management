<?php

namespace App\Helpers;

class PdfHelper {
    
    /**
     * Generates a beautifully styled HTML template of the prescription
     * optimized for browser printing (Ctrl+P / Save to PDF).
     */
    public static function generateHtml(array $p): string {
        $itemsHtml = '';
        $i = 1;
        foreach ($p['items'] as $item) {
            $typeStr = ucfirst($item['medicine_type']);
            $itemsHtml .= "
                <tr style='border-bottom: 1px solid #e2e8f0;'>
                    <td style='padding: 12px 0; font-weight: 600; font-size: 14px;'>
                        {$i}. {$typeStr} {$item['medicine_name']} {$item['strength']}
                        <div style='font-size: 11px; color: #64748b; font-weight: normal; margin-top: 2px;'>
                            {$item['generic_name']}
                        </div>
                    </td>
                    <td style='padding: 12px 0; font-size: 14px; text-align: center;'>{$item['dosage']}</td>
                    <td style='padding: 12px 0; font-size: 14px; text-align: center;'>{$item['frequency']}</td>
                    <td style='padding: 12px 0; font-size: 14px; text-align: right;'>{$item['duration']}</td>
                </tr>
            ";
            $i++;
        }

        $nextVisit = $p['next_visit_date'] ? date('M d, Y', strtotime($p['next_visit_date'])) : 'As needed';
        $rxDate = date('M d, Y', strtotime($p['rx_date']));

        return "
        <!DOCTYPE html>
        <html lang='en'>
        <head>
            <meta charset='UTF-8'>
            <title>Prescription — {$p['prescription_number']}</title>
            <style>
                @media print {
                    body { background: #fff; color: #000; padding: 0; }
                    .no-print { display: none !important; }
                    .print-container { border: none !important; box-shadow: none !important; margin: 0 !important; width: 100% !important; }
                }
                body {
                    font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
                    background: #f8fafc;
                    margin: 0;
                    padding: 40px;
                    display: flex;
                    justify-content: center;
                }
                .print-container {
                    background: #fff;
                    width: 800px;
                    border: 1px solid #e2e8f0;
                    box-shadow: 0 4px 6px -1px rgba(0,0,0,0.05);
                    padding: 48px;
                    box-sizing: border-box;
                    border-radius: 8px;
                }
                .header-table {
                    width: 100%;
                    border-bottom: 3px double #2563eb;
                    padding-bottom: 20px;
                    margin-bottom: 24px;
                }
                .patient-info-bar {
                    background: #f1f5f9;
                    padding: 14px 20px;
                    border-radius: 6px;
                    display: flex;
                    justify-content: space-between;
                    font-size: 13px;
                    color: #334155;
                    margin-bottom: 32px;
                }
                .rx-symbol {
                    font-size: 32px;
                    font-family: Georgia, serif;
                    font-weight: bold;
                    color: #2563eb;
                    margin-bottom: 16px;
                }
            </style>
        </head>
        <body>
            <div class='print-container'>
                <!-- Header -->
                <table class='header-table'>
                    <tr>
                        <td>
                            <h1 style='margin: 0; font-size: 22px; color: #0f172a;'>{$p['doctor_name']}</h1>
                            <p style='margin: 4px 0 0 0; font-size: 13px; color: #475569;'>{$p['doctor_degree']}</p>
                            <p style='margin: 2px 0 0 0; font-size: 13px; color: #2563eb; font-weight: 600;'>{$p['doctor_spec']}</p>
                            <p style='margin: 2px 0 0 0; font-size: 11px; color: #64748b;'>BMDC Reg No: {$p['doctor_bmdc']}</p>
                        </td>
                        <td style='text-align: right; vertical-align: top;'>
                            <h2 style='margin: 0; font-size: 16px; color: #0f172a;'>{$p['chamber_name']}</h2>
                            <p style='margin: 4px 0 0 0; font-size: 12px; color: #475569;'>{$p['chamber_address']}</p>
                            <p style='margin: 2px 0 0 0; font-size: 12px; color: #64748b;'>Date: {$rxDate}</p>
                        </td>
                    </tr>
                </table>

                <!-- Patient Info Bar -->
                <div class='patient-info-bar'>
                    <div><strong>Patient:</strong> {$p['patient_name']}</div>
                    <div><strong>Age/Gender:</strong> {$p['patient_age']} Y / {$p['patient_gender']}</div>
                    <div><strong>Prescription ID:</strong> {$p['prescription_number']}</div>
                </div>

                <!-- Complaints & Diagnoses -->
                <div style='display: grid; grid-template-columns: 1fr 3fr; gap: 32px; min-height: 400px;'>
                    <div style='border-right: 1px solid #e2e8f0; padding-right: 24px;'>
                        <h3 style='font-size: 14px; font-weight: bold; color: #0f172a; margin-top: 0;'>Chief Complaints</h3>
                        <p style='font-size: 13px; color: #475569; line-height: 1.6;'>{$p['chief_complaint']}</p>
                        
                        <h3 style='font-size: 14px; font-weight: bold; color: #0f172a; margin-top: 24px;'>Clinical Diagnosis</h3>
                        <p style='font-size: 13px; color: #475569; line-height: 1.6;'>{$p['diagnosis']}</p>
                    </div>

                    <div>
                        <div class='rx-symbol'>R<sub>x</sub></div>
                        <table style='width: 100%; border-collapse: collapse;'>
                            <thead>
                                <tr style='border-bottom: 2px solid #cbd5e1; text-align: left;'>
                                    <th style='padding-bottom: 8px; font-size: 12px; text-transform: uppercase; color: #64748b;'>Medicine</th>
                                    <th style='padding-bottom: 8px; font-size: 12px; text-transform: uppercase; color: #64748b; text-align: center;'>Dosage</th>
                                    <th style='padding-bottom: 8px; font-size: 12px; text-transform: uppercase; color: #64748b; text-align: center;'>Instruction</th>
                                    <th style='padding-bottom: 8px; font-size: 12px; text-transform: uppercase; color: #64748b; text-align: right;'>Duration</th>
                                </tr>
                            </thead>
                            <tbody>
                                {$itemsHtml}
                            </tbody>
                        </table>

                        <?php if(!empty($p['special_instructions'])): ?>
                        <div style='margin-top: 32px;'>
                            <h4 style='font-size: 13px; margin: 0; color: #0f172a;'>Special Instructions:</h4>
                            <p style='font-size: 13px; color: #475569; margin: 6px 0 0 0;'>{$p['special_instructions']}</p>
                        </div>
                        <?php endif; ?>
                    </div>
                </div>

                <!-- Footer -->
                <div style='margin-top: 64px; border-top: 1px solid #e2e8f0; padding-top: 20px; display: flex; justify-content: space-between; align-items: center; font-size: 11px; color: #94a3b8;'>
                    <div>Next Visit: <strong>{$nextVisit}</strong></div>
                    <div>Generated by Doctor Serial Cloud</div>
                </div>
            </div>
            <script>
                window.onload = function() {
                    window.print();
                }
            </script>
        </body>
        </html>
        ";
    }
}
