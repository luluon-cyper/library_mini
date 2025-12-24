// js/app.js
async function fetchBooks({ title = '', author = '', category = '' } = {}) {
  const params = new URLSearchParams();
  if (title) params.append('title', title);
  if (author) params.append('author', author);
  if (category) params.append('category', category);
  const qs = params.toString();
  const url = qs ? ('php/get_books.php?' + qs) : 'php/get_books.php';
  // Gọi API get_books.php trong thư mục php/ với builder filters
  const res = await fetch(url);
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
      <a class="book-card-link" href="book-detail.php?id=${encodeURIComponent(b.id)}">
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
      </a>
    `;
  }).join('');
}

function escapeHtml(str){
  if(!str) return '';
  return str.replace(/&/g,'&amp;').replace(/</g,'&lt;').replace(/>/g,'&gt;').replace(/"/g,'&quot;').replace(/'/g,'&#039;');
}

document.addEventListener('DOMContentLoaded', async () => {
  const btn = document.getElementById('searchBtn');
  const searchInput = document.getElementById('searchInput');
  const authorInput = document.getElementById('searchAuthor');
  const categoryInput = document.getElementById('searchCategory');

  if(btn){
    btn.addEventListener('click', async () => {
      const kw = searchInput ? searchInput.value.trim() : '';
      const au = authorInput ? authorInput.value.trim() : '';
      const cat = categoryInput ? categoryInput.value.trim() : '';
      const books = await fetchBooks({ title: kw, author: au, category: cat });
      renderBooks(books);
    });
  }
  
  if(searchInput){
    searchInput.addEventListener('keypress', function(e) {
      if (e.key === 'Enter') {
        document.getElementById('searchBtn').click();
      }
    });
  }
  if(authorInput){
    authorInput.addEventListener('keypress', function(e) {
      if (e.key === 'Enter') {
        document.getElementById('searchBtn').click();
      }
    });
  }
  if(categoryInput){
    categoryInput.addEventListener('keypress', function(e) {
      if (e.key === 'Enter') {
        document.getElementById('searchBtn').click();
      }
    });
  }

  // initial load
  const books = await fetchBooks();
  renderBooks(books);
});