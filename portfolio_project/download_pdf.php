<?php
require('fpdf/fpdf.php');

// Database connection
include 'config/db.php'; 

session_start();
$user_id = $_SESSION['user_id'];

// Fetch the latest portfolio data
$sql = "SELECT * FROM portfolios WHERE user_id = ? ORDER BY id DESC LIMIT 1";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$data = $result->fetch_assoc();
$stmt->close();
$conn->close();

// Create PDF
$pdf = new FPDF();
$pdf->AddPage();

// Colors and Fonts
$section_line_color = [54, 115, 177]; // Light blue line color
$font_color = [0, 0, 0]; // Black (for text)

// Remove Dynamic Portfolio header
$pdf->SetTextColor($font_color[0], $font_color[1], $font_color[2]);

// Profile Picture with a Beautiful Border (rounded corners workaround)
if (!empty($data['photo']) && file_exists($data['photo'])) {
    // Set border parameters
    $border_color = [0, 0, 0]; // Black border color
    $border_width = 1; // Thinner border width

    // Set the border color and thickness
    $pdf->SetDrawColor($border_color[0], $border_color[1], $border_color[2]);
    $pdf->SetLineWidth($border_width);
    
    // Draw a rectangle with rounded corners around the image (workaround)
    $pdf->Rect(160 - 2, 10 - 2, 30 + 4, 30 + 4, 'D'); // Border dimensions

    // Insert the image inside the border
    $pdf->Image($data['photo'], 160, 10, 30, 30); // Adjusted position to move image upwards
}

// Reset Font and Colors
$pdf->Ln(10);
$pdf->SetTextColor($font_color[0], $font_color[1], $font_color[2]);
$pdf->SetFont('Arial', 'B', 12);

// Personal Details
$pdf->Cell(30, 10, "Name:", 0, 0);
$pdf->SetFont('Arial', '', 12);
$pdf->Cell(0, 10, $data['name'], 0, 1);

$pdf->SetFont('Arial', 'B', 12);
$pdf->Cell(30, 10, "Contact:", 0, 0);
$pdf->SetFont('Arial', '', 12);
$pdf->Cell(0, 10, $data['contact'], 0, 1);

$pdf->Ln(5);

// Section Headers with Horizontal Line
function addSectionHeader($pdf, $title, $line_color) {
    $pdf->SetFont('Arial', 'B', 12);
    $pdf->Cell(190, 10, $title, 0, 1, 'L');
    $pdf->SetLineWidth(0.5); // Set the thickness of the line
    $pdf->SetDrawColor($line_color[0], $line_color[1], $line_color[2]); // Set the line color
    $pdf->Line(10, $pdf->GetY(), 200, $pdf->GetY()); // Draw the line
    $pdf->Ln(5); // Space after the line
}

// Bio Section
addSectionHeader($pdf, "Bio", $section_line_color);
$pdf->SetFont('Arial', '', 12);
$pdf->MultiCell(190, 8, $data['bio']);
$pdf->Ln(5);

// Skills Section
addSectionHeader($pdf, "Skills", $section_line_color);
$pdf->SetFont('Arial', 'B', 12);
$pdf->Cell(40, 10, "Soft Skills:", 0, 0);
$pdf->SetFont('Arial', '', 12);
$pdf->Cell(0, 10, $data['soft_skills'], 0, 1);

$pdf->SetFont('Arial', 'B', 12);
$pdf->Cell(40, 10, "Technical Skills:", 0, 0);
$pdf->SetFont('Arial', '', 12);
$pdf->Cell(0, 10, $data['technical_skills'], 0, 1);

$pdf->Ln(5);

// Education Section
addSectionHeader($pdf, "Education", $section_line_color);
$pdf->SetFont('Arial', 'B', 12);
$pdf->Cell(40, 10, "BSc Degree:", 0, 0);
$pdf->SetFont('Arial', '', 12);
$pdf->Cell(0, 10, $data['bsc_degree'], 0, 1);

$pdf->SetFont('Arial', 'B', 12);
$pdf->Cell(40, 10, "BSc Institute:", 0, 0);
$pdf->SetFont('Arial', '', 12);
$pdf->Cell(0, 10, $data['bsc_institute'], 0, 1);

$pdf->SetFont('Arial', 'B', 12);
$pdf->Cell(40, 10, "BSc CGPA:", 0, 0);
$pdf->SetFont('Arial', '', 12);
$pdf->Cell(0, 10, $data['bsc_cgpa'], 0, 1);

$pdf->SetFont('Arial', 'B', 12);
$pdf->Cell(40, 10, "BSc Year:", 0, 0);
$pdf->SetFont('Arial', '', 12);
$pdf->Cell(0, 10, $data['bsc_year'], 0, 1);

$pdf->Ln(5);

// MSc Section
$pdf->SetFont('Arial', 'B', 12);
$pdf->Cell(40, 10, "MSc Degree:", 0, 0);
$pdf->SetFont('Arial', '', 12);
$pdf->Cell(0, 10, $data['msc_degree'], 0, 1);

$pdf->SetFont('Arial', 'B', 12);
$pdf->Cell(40, 10, "MSc Institute:", 0, 0);
$pdf->SetFont('Arial', '', 12);
$pdf->Cell(0, 10, $data['msc_institute'], 0, 1);

$pdf->SetFont('Arial', 'B', 12);
$pdf->Cell(40, 10, "MSc CGPA:", 0, 0);
$pdf->SetFont('Arial', '', 12);
$pdf->Cell(0, 10, $data['msc_cgpa'], 0, 1);

$pdf->SetFont('Arial', 'B', 12);
$pdf->Cell(40, 10, "MSc Year:", 0, 0);
$pdf->SetFont('Arial', '', 12);
$pdf->Cell(0, 10, $data['msc_year'], 0, 1);

$pdf->Ln(5);

// Experience Section
addSectionHeader($pdf, "Experience", $section_line_color);
$pdf->SetFont('Arial', '', 12);
$pdf->MultiCell(190, 8, $data['experience']);
$pdf->Ln(5);

// Projects Section
addSectionHeader($pdf, "Projects", $section_line_color);
$pdf->SetFont('Arial', '', 12);
$pdf->MultiCell(190, 8, $data['projects']);
$pdf->Ln(5);

// Output PDF
$pdf->Output();
?>
