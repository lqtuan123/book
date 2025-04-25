// Kiểm tra có tồn tại global window.BookMentionLoaded chưa
if (window.BookMentionLoaded) {
    // console.log('BookMention đã được tải, bỏ qua.');
} else {
    // Đánh dấu đã tải
    window.BookMentionLoaded = true;
    
    class BookMention {
        constructor(inputElement) {
            // Kiểm tra nếu đã khởi tạo BookMention cho element này rồi thì return
            if (inputElement.bookMentionInitialized) {
                return;
            }
            
            // Đánh dấu element đã được khởi tạo
            inputElement.bookMentionInitialized = true;
            
            this.input = inputElement;
            this.suggestionBox = this.createSuggestionBox();
            this.mentionTrigger = '@';
            this.currentMentionPosition = null;
            this.setupEventListeners();
            // console.log('BookMention initialized for', inputElement);
        }
    
        createSuggestionBox() {
            const box = document.createElement('div');
            box.className = 'mention-suggestions';
            box.style.cssText = `
                position: absolute;
                display: none;
                background: white;
                border: 1px solid #ddd;
                border-radius: 4px;
                max-height: 200px;
                overflow-y: auto;
                z-index: 1000;
                box-shadow: 0 2px 5px rgba(0,0,0,0.1);
            `;
            document.body.appendChild(box);
            return box;
        }
    
        setupEventListeners() {
            this.input.addEventListener('input', this.handleInput.bind(this));
            this.input.addEventListener('keydown', this.handleKeydown.bind(this));
            document.addEventListener('click', (e) => {
                if (!this.suggestionBox.contains(e.target) && e.target !== this.input) {
                    this.hideSuggestions();
                }
            });
        }
    
        async handleInput(e) {
            const cursorPosition = this.input.selectionStart;
            const textBeforeCursor = this.input.value.substring(0, cursorPosition);
            const lastAtSymbol = textBeforeCursor.lastIndexOf(this.mentionTrigger);
    
            // console.log('Input detected:', e.data);
            // console.log('Current text:', this.input.value);
            // console.log('At symbol position:', lastAtSymbol);
    
            if (lastAtSymbol !== -1) {
                const textAfterAt = textBeforeCursor.substring(lastAtSymbol + 1);
                // console.log('Text after @:', textAfterAt);
                
                if (!textAfterAt.includes(' ') && textAfterAt.length > 0) {
                    this.currentMentionPosition = lastAtSymbol;
                    await this.showSuggestions(textAfterAt);
                    return;
                }
            }
    
            this.hideSuggestions();
        }
    
        async showSuggestions(query) {
            try {
                // console.log('Searching for:', query);
                const baseUrl = window.location.origin;
                const apiUrl = `${baseUrl}/books/search-mention?query=${encodeURIComponent(query)}`;
                // console.log('API URL:', apiUrl);
                
                const response = await fetch(apiUrl);
                const books = await response.json();
                // console.log('Found books:', books);
    
                if (books.length === 0) {
                    this.hideSuggestions();
                    return;
                }
    
                this.suggestionBox.innerHTML = books.map(book => `
                    <div class="mention-suggestion" data-book-id="${book.id}" data-book-title="${book.title}" data-book-url="${book.url}">
                        ${book.title}
                    </div>
                `).join('');
    
                this.suggestionBox.style.display = 'block';
                
                // Position the suggestion box
                const inputRect = this.input.getBoundingClientRect();
                this.suggestionBox.style.top = `${inputRect.bottom + window.scrollY}px`;
                this.suggestionBox.style.left = `${inputRect.left + window.scrollX}px`;
                this.suggestionBox.style.width = `${inputRect.width}px`;
    
                // Add click handlers
                this.suggestionBox.querySelectorAll('.mention-suggestion').forEach(suggestion => {
                    suggestion.addEventListener('click', () => this.selectSuggestion(suggestion));
                });
            } catch (error) {
                console.error('Error fetching book suggestions:', error);
            }
        }
    
        selectSuggestion(suggestion) {
            const bookTitle = suggestion.dataset.bookTitle;
            const bookUrl = suggestion.dataset.bookUrl;
            const bookId = suggestion.dataset.bookId;
            const originalText = this.input.value;
            
            // Replace the @query with just the book title (more user-friendly)
            const beforeMention = originalText.substring(0, this.currentMentionPosition);
            const afterMention = originalText.substring(this.input.selectionStart);
            
            // Chỉ hiển thị "@Tên sách" trong textarea thay vì "@[Tên sách](URL)"
            const displayText = `@${bookTitle}`;
            
            // Tạo một div ẩn chứa dữ liệu đầy đủ để xử lý khi submit
            if (!document.getElementById('book-mentions-data')) {
                const hiddenData = document.createElement('div');
                hiddenData.id = 'book-mentions-data';
                hiddenData.style.display = 'none';
                document.body.appendChild(hiddenData);
            }
            
            // Tạo ID duy nhất cho mention này
            const mentionId = `mention-${Date.now()}-${Math.floor(Math.random() * 1000)}`;
            
            // Lưu dữ liệu vào div ẩn
            const mentionData = { id: mentionId, bookId, bookTitle, bookUrl };
            const hiddenData = document.getElementById('book-mentions-data');
            hiddenData.setAttribute(`data-${mentionId}`, JSON.stringify(mentionData));
            
            // Thêm thuộc tính data-mention-id vào text để có thể nhận dạng khi submit
            const mentionHtml = `${displayText}[${mentionId}]`;
            
            this.input.value = beforeMention + mentionHtml + afterMention;
            this.hideSuggestions();
        }
    
        handleKeydown(e) {
            if (!this.suggestionBox.style.display || this.suggestionBox.style.display === 'none') {
                return;
            }
    
            const suggestions = this.suggestionBox.querySelectorAll('.mention-suggestion');
            const currentIndex = Array.from(suggestions).findIndex(el => el.classList.contains('active'));
    
            switch (e.key) {
                case 'ArrowDown':
                    e.preventDefault();
                    this.navigateSuggestions(currentIndex, 1, suggestions);
                    break;
                case 'ArrowUp':
                    e.preventDefault();
                    this.navigateSuggestions(currentIndex, -1, suggestions);
                    break;
                case 'Enter':
                    e.preventDefault();
                    const activeSuggestion = this.suggestionBox.querySelector('.mention-suggestion.active');
                    if (activeSuggestion) {
                        this.selectSuggestion(activeSuggestion);
                    } else if (suggestions.length > 0) {
                        this.selectSuggestion(suggestions[0]);
                    }
                    break;
                case 'Escape':
                    this.hideSuggestions();
                    break;
            }
        }
    
        navigateSuggestions(currentIndex, direction, suggestions) {
            suggestions.forEach(s => s.classList.remove('active'));
            let newIndex = currentIndex + direction;
            
            if (newIndex < 0) newIndex = suggestions.length - 1;
            if (newIndex >= suggestions.length) newIndex = 0;
            
            suggestions[newIndex].classList.add('active');
            suggestions[newIndex].scrollIntoView({ block: 'nearest' });
        }
    
        hideSuggestions() {
            this.suggestionBox.style.display = 'none';
            this.currentMentionPosition = null;
        }
    }
    
    // Add styles
    const style = document.createElement('style');
    style.textContent = `
        .mention-suggestion {
            padding: 8px 12px;
            cursor: pointer;
            transition: background-color 0.2s;
        }
        
        .mention-suggestion:hover,
        .mention-suggestion.active {
            background-color: #f0f0f0;
        }
        
        .book-mention {
            color: #2196F3;
            text-decoration: none;
            font-weight: 500;
        }
        
        .book-mention:hover {
            text-decoration: underline;
        }
    `;
    document.head.appendChild(style);
    
    // console.log('Book mention script loaded');
    
    // Tạo biến global để lưu trữ bookMentionInitialized
    window.bookMentionInitialized = false;
    
    // Khởi tạo BookMention cho tất cả textarea với class 'comment-textarea'
    // Sử dụng một hàm duy nhất để khởi tạo, tránh lặp lại
    function initializeBookMentions() {
        if (window.bookMentionInitialized) {
            // console.log('BookMention đã được khởi tạo, bỏ qua.');
            return;
        }
        
        window.bookMentionInitialized = true;
        // console.log('Initializing BookMention globally...');
        
        // Khởi tạo cho các textarea hiện tại
        document.querySelectorAll('.comment-textarea:not(.mention-initialized)').forEach(textarea => {
            textarea.classList.add('mention-initialized');
            new BookMention(textarea);
        });
        
        // Theo dõi các textarea mới được thêm vào DOM
        const observer = new MutationObserver(function(mutations) {
            mutations.forEach(function(mutation) {
                if (mutation.addedNodes.length) {
                    mutation.addedNodes.forEach(function(node) {
                        if (node.nodeType === 1) { // ELEMENT_NODE
                            const textareas = node.querySelectorAll ? node.querySelectorAll('.comment-textarea:not(.mention-initialized)') : [];
                            textareas.forEach(textarea => {
                                textarea.classList.add('mention-initialized');
                                new BookMention(textarea);
                            });
                        }
                    });
                }
            });
        });
        
        observer.observe(document.body, { childList: true, subtree: true });
    }
    
    // Đăng ký sự kiện DOMContentLoaded một lần duy nhất
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', initializeBookMentions);
    } else {
        // DOMContentLoaded đã xảy ra rồi, khởi tạo ngay lập tức
        initializeBookMentions();
    }
} 