// js/app.js
async function fetchBooks(keyword='') {
  // Gọi API get_books.php trong thư mục php/
  const res = await fetch('php/get_books.php?keyword=' + encodeURIComponent(keyword));
  return await res.json();
}

function renderBooks(books) {
  const c = document.getElementById('booksContainer');
  if(!c) return;
  if(!books.length) {
    c.innerHTML = '<p>Không có sách phù hợp.</p>';
    return;
  }
  
  c.innerHTML = books.map(b => {
    // b.status nhận giá trị 'available' hoặc 'borrowed' từ PHP/SQL (cột TinhTrang)
    const statusClass = b.status === 'available' ? 'available' : 'borrowed';
    const statusText = b.status === 'available' ? 'Có sẵn' : 'Đã mượn';
    const image = b.image || 'https://dayve.vn/wp-content/uploads/2022/11/Ve-quyen-sach-Buoc-16.jpg';

    // b.title, b.author, b.category nhận từ bí danh AS trong SQL
    return `
      <div class="book-card">
        <div class="book-thumb">
          <img src="${image}" alt="${escapeHtml(b.title)}" loading="lazy">
        </div>
        <div class="book-info">
          <h3>${escapeHtml(b.title)}</h3>
          <p>Tác giả: ${escapeHtml(b.author || 'Chưa rõ')}</p>
          <p>Thể loại: ${escapeHtml(b.category || 'Chưa rõ')}</p>
          <p>Trạng thái: 
            <span class="book-status ${statusClass}">${statusText}</span>
          </p>
        </div>
      </div>
    `;
  }).join('');
}

function escapeHtml(str){
  if(!str) return '';
  return str.replace(/&/g,'&amp;').replace(/</g,'&lt;').replace(/>/g,'&gt;').replace(/"/g,'&quot;').replace(/'/g,'&#039;');
}

document.addEventListener('DOMContentLoaded', async () => {
  const btn = document.getElementById('searchBtn');
  if(btn){
    btn.addEventListener('click', async () => {
      const kw = document.getElementById('searchInput').value.trim();
      const books = await fetchBooks(kw);
      renderBooks(books);
    });
  }
  // Thêm sự kiện Enter cho ô tìm kiếm
  const searchInput = document.getElementById('searchInput');
  if(searchInput){
    searchInput.addEventListener('keypress', function(e) {
      if (e.key === 'Enter') {
        document.getElementById('searchBtn').click();
      }
    });
  }

  // initial load
  const books = await fetchBooks();
  renderBooks(books);
});