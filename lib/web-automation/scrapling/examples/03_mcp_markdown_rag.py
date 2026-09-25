"""MCP cho AI agent + SiteToMarkdown RAG (trọng tâm bạn chọn)."""
# MCP: cho Claude/Cursor đọc Scrapling qua mcp.json (scrapling mcp serve)
# Pages được strip prompt-injection trước khi AI thấy → đọc ít hơn, rẻ hơn.

# RAG-ready markdown không LLM:
from scrapling.fetchers import Fetcher
page = Fetcher.get('https://example.com')
md = page.markdown()  # clean, sanitized
print(md[:800])

# Crawl cả site thành corpus Markdown (không LLM):
# from scrapling.spiders import SiteToMarkdownSpider
# class DocsToMd(SiteToMarkdownSpider):
#     target_website = "example.com"
# DocsToMd().start()
