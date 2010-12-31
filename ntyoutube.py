import web
import YouTubeURLMaker

urls = (
	'/watch', 'ntyoutube'
)
app = web.application(urls, globals())

class ntyoutube:
	def GET(self):
		url = "http://youtube.com/watch?v=" + web.input().v
		hd = False
		if web.input().hd == "1":
			hd = True
		web.redirect(YouTubeURLMaker.getYouTubeDownloadLink(url, hd))

if __name__ == "__main__":
	app.run()