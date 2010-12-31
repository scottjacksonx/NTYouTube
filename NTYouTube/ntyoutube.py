import web
import YouTubeURLMaker
from google.appengine.ext.webapp.util import run_wsgi_app

urls = (
	'/watch', 'ntyoutube',
)
app = web.application(urls, globals())

class ntyoutube:
	def GET(self):
		url = "http://youtube.com/watch?v=" + web.input().v
		hd = False
		if web.input(hd="0").hd == "1":
			hd = True
		web.redirect(YouTubeURLMaker.getYouTubeDownloadLink(url, hd))

def main():
    application = app.wsgifunc()
    run_wsgi_app(application)

if __name__ == '__main__':
    main()