
async function fetchBooks({ title = '', author = '', category = '' } = {}) {
  const params = new URLSearchParams();
  if (title) params.append('title', title);
  if (author) params.append('author', author);
  if (category) params.append('category', category);
  const qs = params.toString();
  const url = qs ? ('php/get_books.php?' + qs) : 'php/get_books.php';

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
    const statusClass = b.status === 'available' ? 'available' : 'borrowed';
    const statusText = b.status === 'available' ? 'Có sẵn' : 'Đã mượn';
    const image = b.image || 'https://dayve.vn/wp-content/uploads/2022/11/Ve-quyen-sach-Buoc-16.jpg';

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
  const searchTypeBtn = document.getElementById('searchTypeBtn');
  const searchTypeMenu = document.getElementById('searchTypeMenu');
  const searchTypeLabel = document.getElementById('searchTypeLabel');
  let currentType = 'title';

  if(btn){
    btn.addEventListener('click', async () => {
      const kw = searchInput ? searchInput.value.trim() : '';
      const payload = { title: '', author: '', category: '' };
      if (currentType === 'title') payload.title = kw;
      if (currentType === 'author') payload.author = kw;
      if (currentType === 'category') payload.category = kw;
      const books = await fetchBooks(payload);
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
  if(searchTypeBtn && searchTypeMenu){
    searchTypeBtn.addEventListener('click', () => {
      const isHidden = searchTypeMenu.style.display === 'none' || searchTypeMenu.style.display === '';
      searchTypeMenu.style.display = isHidden ? 'block' : 'none';
    });
    searchTypeMenu.querySelectorAll('li').forEach(li => {
      li.addEventListener('click', () => {
        currentType = li.dataset.type || 'title';
        const ph = li.dataset.placeholder || 'Nhập từ khóa...';
        if (searchTypeLabel) searchTypeLabel.textContent = li.textContent.trim();
        if (searchInput) searchInput.placeholder = ph;
        searchTypeMenu.style.display = 'none';
        searchInput && searchInput.focus();
      });
    });
    document.addEventListener('click', (e) => {
      if (!searchTypeBtn.contains(e.target) && !searchTypeMenu.contains(e.target)) {
        searchTypeMenu.style.display = 'none';
      }
    });
  }

  const books = await fetchBooks();
  renderBooks(books);
});