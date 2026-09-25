"""One-shot fetch + adaptive selectors — dùng nhiều (Fetcher + auto_save/adaptive)."""
from scrapling.fetchers import Fetcher

# Cơ bản
page = Fetcher.get('https://quotes.toscrape.com/')
print(page.css('.quote .text::text').getall()[:2])

# Adaptive: lưu selector, khi site đổi thì tự relocate
p = Fetcher.get('https://example.com')
products = p.css('.product', auto_save=True)  # lần đầu lưu
products = p.css('.product', adaptive=True)   # lần sau tự tìm lại khi layout đổi

# RAG-ready markdown không cần LLM
print(page.markdown()[:500])

# XHR capture
# page = Fetcher.get('https://example.com', capture_xhr='api/products')
# print(page.captured_xhr)
