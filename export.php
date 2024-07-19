<?php
require_once 'includes/header.php';
require_once 'classes/Database.php';
require_once 'classes/Post.php';
require_once 'vendor/autoload.php'; 

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

$database = new Database();
$db = $database->getConnection();
$post = new Post($db);

$stmt = $post->read();

$spreadsheet = new Spreadsheet();
$sheet = $spreadsheet->getActiveSheet();

$sheet->setCellValue('A1', 'Title');
$sheet->setCellValue('B1', 'Content');
$sheet->setCellValue('C1', 'Author');
$sheet->setCellValue('D1', 'Created At');

$row = 2;
while ($post = $stmt->fetch(PDO::FETCH_ASSOC)) {
    $sheet->setCellValue('A' . $row, $post['title']);
    $sheet->setCellValue('B' . $row, $post['content']);
    $sheet->setCellValue('C' . $row, $post['username']);
    $sheet->setCellValue('D' . $row, $post['created_at']);
    $row++;
}

$writer = new Xlsx($spreadsheet);
$filename = 'blog_posts_export_' . date('Y-m-d') . '.xlsx';
header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header('Content-Disposition: attachment; filename="'. urlencode($filename).'"');
$writer->save('php://output');
exit;