from urllib.parse import urlencode

from lxml import etree
from json import loads

from bs4 import BeautifulSoup
from urllib.request import urlopen, Request
from re import search
import traceback
from requests_html import HTMLSession



for pageNu in range(0, 508):
    try:
        session = HTMLSession()
        urlToFetch = 'https://www.autotrader.ca/cars/?rcp=100&rcs=' + str(
            pageNu * 100) + '&srt=3&yRng=2022%2C2024&prx=-1&hprc=True&wcp=True&inMarket=advancedSearch'

        r = session.get(urlToFetch)
        print(str(pageNu) + "  " + urlToFetch)
        linksArray = []
        for item in r.html.links:
            if 'showcpo' in str(item):
                item = item.split("?showcpo")
                item = "https://www.autotrader.ca" + item[0]
                linksArray.append(item)
                # linksArray = set(linksArray)

        linksArray = set(linksArray)
        fil = open("links2022.txt", "a")
        for i in linksArray:
            # print(i)
            # getDataAndPost(i)
            fil.write(i + "\n")
        fil.close()
        session.close()
    except:
        traceback.print_exc()
