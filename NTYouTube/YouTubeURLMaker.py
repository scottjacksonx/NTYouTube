import urllib

def getYouTubeDownloadLink(videoURL, hd):
    if not hd:
        videoURL += "&fmt=18"
    htmlSource = urllib.urlopen(videoURL).read()
    
    fmt = ""
    if htmlSource.find("'IS_HD_AVAILABLE': true") != -1 and hd:
        fmt = "22"
    else:
        htmlSource = urllib.urlopen(videoURL+"&fmt=18").read()
        fmt = "18"
        
    downloadURLStart = htmlSource.find(fmt + "|")
    downloadURLStart += 3
    downloadURLEnd = htmlSource.find(",", downloadURLStart + 1)
    downloadURL = htmlSource[downloadURLStart:downloadURLEnd]
    downloadURL = downloadURL.replace("\/", "/")
    
    
    videoTitleStart = htmlSource.find("<meta name=\"title\" content=\"") 
    videoTitleEnd = htmlSource.find("\">", videoTitleStart + 1)
    videoTitleStart += 28
    videoTitle = htmlSource[videoTitleStart:videoTitleEnd]
    videoTitle = videoTitle.replace("&quot;", "\"")
    videoTitle = videoTitle.replace("&amp;", "&")
    
#    return (downloadURL, videoTitle, fmt)
    return downloadURL