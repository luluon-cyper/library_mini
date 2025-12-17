<?php
// Cấu hình nghiệp vụ mượn trả
return [
    'DEFAULT_DAYS' => 36,   // Thời hạn mượn mặc định (ngày)
    'MAX_DAYS'      => 54,  // Tổng thời gian tối đa tính từ ngày mượn (cho phép gia hạn +18)
    'EXTEND_DAYS'   => 18,  // Số ngày gia hạn mỗi lần
    'OVERDUE_FEE'   => 1000 // Phí quá hạn mỗi ngày trễ (đồng)
];

