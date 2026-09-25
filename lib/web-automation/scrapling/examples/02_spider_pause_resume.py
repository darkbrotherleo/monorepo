"""Spider concurrent + pause/resume + export — Scale crawl."""
from scrapling.spiders import Spider, Response

class DemoSpider(Spider):
    name = "demo"
    start_urls = ["https://quotes.toscrape.com/"]
    concurrent_requests = 10

    async def parse(self, response: Response):
        for q in response.css('.quote'):
            yield {"text": q.css('.text::text').get(), "author": q.css('.author::text').get()}
        nxt = response.css('.next a')
        if nxt:
            yield response.follow(nxt[0].attrib['href'])

# Pause/resume: Ctrl+C tự checkpoint
result = DemoSpider(crawldir="./crawl_data").start()
result.items.to_json("quotes.json")
print(f"Scraped {len(result.items)} items")
