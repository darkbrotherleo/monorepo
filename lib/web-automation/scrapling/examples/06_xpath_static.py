"""XPath trang tĩnh — Scrapling page.xpath (tương đương PHP DOMXPath trong cao-xpath.php)

Trang tĩnh thì XPath chạy trực tiếp, không cần browser.
Scrapling hỗ trợ XPath qua lxml, cùng API với CSS + adaptive.
"""
# Fix cp1252
import sys
if hasattr(sys.stdout, "reconfigure"):
    sys.stdout.reconfigure(encoding="utf-8", errors="replace")

from scrapling.fetchers import Fetcher

# Demo với quotes.toscrape.com (trang tĩnh mẫu)
url = "https://quotes.toscrape.com/"
print(f"Fetch {url} ...")
page = Fetcher.get(url)
print(f"Status {page.status}")

# XPath cơ bản — tương đương CSS
# CSS: page.css('.quote')
# XPath: page.xpath('//div[@class="quote"]')
quotes = page.css('.quote')
quotes_xp = page.xpath('//div[@class="quote"]')
print(f"CSS .quote: {len(quotes)} | XPath //div[@class=\"quote\"]: {len(quotes_xp)}")

# Lấy text/attr
# CSS: page.css('h1::text').get() | page.css('a::attr(href)').get()
# XPath: page.xpath('//h1//text()').get() | page.xpath('//a/@href').get()
title = page.css('h1::text').get()
title_xp = page.xpath('//h1//text()').get()
print(f"Title CSS: {title!r} | XPath: {title_xp!r}")

# Ví dụ: lấy Tên/giá/đã bán bằng XPath (trên trang tĩnh demo)
# Mỗi quote có: text, author, tags
for q in page.xpath('//div[@class="quote"]')[:3]:
    text = q.xpath('.//span[@class="text"]/text()').get()
    author = q.xpath('.//small[@class="author"]/text()').get()
    tags = q.xpath('.//div[@class="tags"]/a/text()').getall()
    print(f" - {text[:50]!r} | author={author} | tags={tags[:2]}")

# Adaptive với XPath — khi site đổi layout, vẫn relocate
# Bật adaptive khi khởi tạo
# page2 = Fetcher.get(url, adaptive=True) # hoặc Fetcher.adaptive = True
# element = page2.xpath('//div[@class="quote"]', auto_save=True)
# ... sau này site đổi class ...
# element = page2.xpath('//div[@class="quote"]', adaptive=True)  # tự tìm lại

# Chain CSS + XPath
print("\nChain CSS + XPath:")
mixed = page.xpath('//div[@class="quote"]')[0].css('small.author::text').get()
print(f" Mixed xpath->css: {mixed!r}")

# Generate selector
first = page.xpath('//div[@class="quote"]')[0]
print(f"\nGenerate XPath: {first.generate_xpath_selector}")
print(f"Full XPath: {first.generate_full_xpath_selector[:80]}...")

print("\nDone — XPath chạy trực tiếp trên HTML tĩnh, không cần browser.")
