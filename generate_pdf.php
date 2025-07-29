<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once __DIR__ . '/fpdf/fpdf.php';          
require_once __DIR__ . '/fpdi/src/autoload.php';

use setasign\Fpdi\Fpdi;

// Новые данные из формы
$anrede  = $_POST['anrede'] ?? '';        // Frau / Herr / divers
$telefon = $_POST['telefon'] ?? '';       // Телефон

// Данные из формы (страница 1)
$name         = $_POST['name'] ?? 'Mustermann';
$vorname      = $_POST['vorname'] ?? 'Max';
$geburtsdatum = $_POST['geburtsdatum'] ?? '01.01.1990';

$neu_strasse  = $_POST['neu_strasse'] ?? 'Beispielstraße 12';
$neu_plz      = $_POST['neu_plz'] ?? '86156';
$neu_ort      = $_POST['neu_ort'] ?? 'Augsburg';
$einzugsdatum = $_POST['einzugsdatum'] ?? '01.08.2025';

$alt_strasse  = $_POST['alt_strasse'] ?? 'Alte Straße 1';
$alt_plz      = $_POST['alt_plz'] ?? '12345';
$alt_ort      = $_POST['alt_ort'] ?? 'Alterstadt';

$jobcenter         = $_POST['jobcenter'] ?? 'nein';
$briefkasten       = $_POST['briefkasten'] ?? 'nein';
$anmeldung         = $_POST['anmeldung'] ?? 'nein';
$kostenaufstellung = $_POST['kostenaufstellung'] ?? 'nein';
$wohnflaeche       = $_POST['wohnflaeche'] ?? '';
$anzahl_personen   = $_POST['anzahl_personen'] ?? '';
$andere_leistungen = $_POST['andere_leistungen'] ?? 'nein';
$andere_name       = $_POST['andere_name'] ?? '';
$bg_nummer         = $_POST['bg_nummer'] ?? '';

// Данные из формы (страница 2)
$alle_familie             = $_POST['alle_familie'] ?? 'nein';
$familienangehoerige      = $_POST['familienangehoerige'] ?? '';
$personen_ueber_25        = $_POST['personen_ueber_25'] ?? 'nein';
$anzahl_personen_ueber_25 = $_POST['anzahl_personen_ueber_25'] ?? '';
$wohngemeinschaft         = $_POST['wohngemeinschaft'] ?? 'nein';
$eigener_mietvertrag      = $_POST['eigener_mietvertrag'] ?? 'nein';
$untermietvertraege       = $_POST['untermietvertraege'] ?? 'nein';
$umzugsgrund              = $_POST['umzugsgrund'] ?? '';

$templatePath = __DIR__ . "/Fragebogen_Mietbestätigung.pdf";

if (!file_exists($templatePath)) {
    die("Error PDF");
}

$pdf = new Fpdi();
$pageCount = $pdf->setSourceFile($templatePath);

// Функция рисования крестика для Ja/Nein
function drawCheckmark(Fpdi $pdf, $xJa, $xNein, $y, $value) {
    $pdf->SetFont('Helvetica', '', 11);
    $check = 'x';

    if (strtolower($value) === 'ja') {
        $pdf->SetXY($xJa, $y);
        $pdf->Write(5, $check);
    } elseif (strtolower($value) === 'nein') {
        $pdf->SetXY($xNein, $y);
        $pdf->Write(5, $check);
    }
}

for ($pageNo = 1; $pageNo <= $pageCount; $pageNo++) {
    $pdf->AddPage();
    $templateId = $pdf->importPage($pageNo);
    $pdf->useTemplate($templateId);

    $pdf->SetFont('Helvetica', '', 11);
    $pdf->SetTextColor(0, 0, 0);

    if ($pageNo == 1) {
        // Крестики Frau / Herr / divers (точные координаты под PDF)
        if ($anrede == 'Frau') { 
            $pdf->SetXY(27, 96); $pdf->Write(5, 'x'); 
        } elseif ($anrede == 'Herr') { 
            $pdf->SetXY(47, 96); $pdf->Write(5, 'x'); 
        } elseif ($anrede == 'divers') { 
            $pdf->SetXY(64, 96); $pdf->Write(5, 'x'); 
        }

        // Телефон
        $pdf->SetXY(115, 101);
        $pdf->Write(5, $telefon);

        // Основные поля
        $pdf->SetXY(62, 107);  $pdf->Write(5, $name);
        $pdf->SetXY(63, 115);  $pdf->Write(5, $vorname);
        $pdf->SetXY(63, 122);  $pdf->Write(5, $geburtsdatum);

        // Старый адрес
        $pdf->SetXY(63, 143);  $pdf->Write(5, $alt_strasse);
        $pdf->SetXY(63, 150);  $pdf->Write(5, $alt_plz);
        $pdf->SetXY(63, 157);  $pdf->Write(5, $alt_ort);

        // Новый адрес
        $pdf->SetXY(63, 181);  $pdf->Write(5, $neu_strasse);
        $pdf->SetXY(63, 188);  $pdf->Write(5, $neu_plz);
        $pdf->SetXY(63, 194);  $pdf->Write(5, $neu_ort);
        $pdf->SetXY(63, 200);  $pdf->Write(5, $einzugsdatum);

        // Дополнительные текстовые поля
        $pdf->SetXY(162, 235);  $pdf->Write(5,  $wohnflaeche );
        $pdf->SetXY(162, 242);  $pdf->Write(5,  $anzahl_personen);

        if (strtolower($andere_leistungen) === 'ja') {
            $pdf->SetXY(85, 255);  $pdf->Write(5,  $andere_name);
            $pdf->SetXY(93, 262);  $pdf->Write(5,   $bg_nummer);
        }

        // Ja/Nein блоки
        drawCheckmark($pdf, 162, 175, 208, $jobcenter);         
        drawCheckmark($pdf, 162, 175, 215, $briefkasten);       
        drawCheckmark($pdf, 162, 175, 222, $anmeldung);         
        drawCheckmark($pdf, 162, 175, 229, $kostenaufstellung); 
        drawCheckmark($pdf, 162, 175, 250, $andere_leistungen); 
    }

    if ($pageNo == 2) {
        // Страница 2
        drawCheckmark($pdf, 163, 175, 20, $alle_familie);

        $pdf->SetXY(44, 36);
        $pdf->MultiCell(0, 5, $familienangehoerige);

        drawCheckmark($pdf, 162, 175, 47, $personen_ueber_25);

        $pdf->SetXY(162, 53);
        $pdf->Write(5, $anzahl_personen_ueber_25);

        drawCheckmark($pdf, 162, 175, 63, $wohngemeinschaft);
        drawCheckmark($pdf, 162, 175, 70, $eigener_mietvertrag);
        drawCheckmark($pdf, 162, 175, 78, $untermietvertraege);

        $pdf->SetXY(25, 107);
        $pdf->MultiCell(0, 5, $umzugsgrund);
    }
}

header('Content-Type: application/pdf');
header('Content-Disposition: attachment; filename="Mietbestaetigung_ausgefuellt.pdf"');
header('Cache-Control: private, max-age=0, must-revalidate');
header('Pragma: public');

$pdf->Output('D', 'Mietbestaetigung_ausgefuellt.pdf');
exit;
?>
