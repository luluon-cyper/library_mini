<?php
header('Content-Type: application/json; charset=utf-8');
require 'config.php';
$conn = getConn();

$title    = trim($_GET['keyword'] ?? $_GET['title'] ?? '');
$author   = trim($_GET['author'] ?? '');
$category = trim($_GET['category'] ?? '');

class BookQueryBuilder {
    private string $base;
    private array $conditions = [];
    private array $params = [];
    private string $types = '';

    public function __construct() {
        $this->base = "SELECT 
            s.IDSach AS id, 
            s.TenSach AS title, 
            s.Anh AS image,
            tg.TenTacGia AS author, 
            tl.TenTheLoai AS category, 
            s.TinhTrang AS status 
        FROM sach s
        LEFT JOIN tacgia tg ON s.IDTacGia = tg.IDTacGia
        LEFT JOIN theloai tl ON s.IDTheLoai = tl.IDTheLoai";
    }

    public function byTitle(string $title): self {
        if ($title !== '') {
            $this->conditions[] = 's.TenSach LIKE ?';
            $this->params[] = '%' . $title . '%';
            $this->types .= 's';
        }
        return $this;
    }
    
    public function byAuthor(string $author): self {
        if ($author !== '') {
            $this->conditions[] = 'tg.TenTacGia LIKE ?';
            $this->params[] = '%' . $author . '%';
            $this->types .= 's';
        }
        return $this;
    }
    public function byCategory(string $category): self {
        if ($category !== '') {
            $this->conditions[] = 'tl.TenTheLoai LIKE ?';
            $this->params[] = '%' . $category . '%';
            $this->types .= 's';
        }
        return $this;
    }
    public function build(mysqli $conn): mysqli_stmt {
        $sql = $this->base;
        if (!empty($this->conditions)) {
            $sql .= ' WHERE ' . implode(' AND ', $this->conditions);
        }
        $sql .= ' ORDER BY s.IDSach DESC';
        $stmt = $conn->prepare($sql);
        if (!$stmt) {
            throw new RuntimeException('Cannot prepare statement');
        }
        if (!empty($this->params)) {
            $stmt->bind_param($this->types, ...$this->params);
        }
        return $stmt;
    }
}

try {
    $builder = (new BookQueryBuilder())
        ->byTitle($title)
        ->byAuthor($author)
        ->byCategory($category);

    $stmt = $builder->build($conn);
    $stmt->execute();
    $res = $stmt->get_result();
    $books = [];
    $fallback_img = 'https://dayve.vn/wp-content/uploads/2022/11/Ve-quyen-sach-Buoc-16.jpg';
    while($r = $res->fetch_assoc()) {
        if(!isset($r['image']) || !$r['image']){
            $r['image'] = $fallback_img;
        }
        $books[] = $r;
    }
    echo json_encode($books, JSON_UNESCAPED_UNICODE);
} catch (Throwable $e) {
    http_response_code(500);
    echo json_encode(['error' => 'Server error'], JSON_UNESCAPED_UNICODE);
}
?>