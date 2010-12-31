import web
import YouTubeURLMaker

urls = (
	'/watch', 'ntyoutube'
)
app = web.application(urls, globals())

class ntyoutube:
	def GET(self):
		url = "http://youtube.com/watch?v=" + web.input().v
		web.redirect(YouTubeURLMaker.getYouTubeDownloadLink(url, False))

if __name__ == "__main__":
	app.run()