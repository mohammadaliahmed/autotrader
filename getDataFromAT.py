from json import loads
import traceback
from requests_html import HTMLSession
from bs4 import BeautifulSoup
import json



temp = open("links2022.txt",'r').read().splitlines()

filenameCount=0
for line in range(0,50358):
    if line%1000==0:
        filenameCount=filenameCount+1
    print("Pg:"+ str(line))
    newUrl=temp[line]
    try:
        session = HTMLSession()
        rs = session.get(newUrl)
        abc = rs.html.raw_html
        soup = BeautifulSoup(abc, "lxml")
        scripts = soup.find_all('script', {'type': 'text/javascript'})
        for script in scripts:
            if "window['ngVdpModel']" in str(script):
                datas = script.text.split("\n")[3]
                finlJson = datas.replace("window['ngVdpModel']", '"APP"').strip()
                finlJson = finlJson.replace("=", ":").replace(";", "")
                jsons = "{" + finlJson + "}"
                data = loads(jsons)

                make = str(data.get('APP').get("hero").get("make"))
                try:
                    description = str(data.get('APP').get("description").get("description")[0].get("description"))
                except:
                    description = ""
                year = str(data.get('APP').get("hero").get("year"))
                model = str(data.get('APP').get("hero").get("model"))
                price = str(data.get('APP').get("hero").get("price"))
                trim = str(data.get('APP').get("hero").get("trim"))
                title = year + " " + make + " " + model + " " + trim
                mileage = str(data.get('APP').get("hero").get("mileage"))
                location = str(data.get('APP').get("hero").get("location"))
                try:
                    mainCategory = data.get('APP').get("carInsurance").get("micrositeText")
                except:
                    mainCategory = "Cars, Trucks & SUVs"

                try:
                    googleRating = data.get('APP').get("dealerTrust").get("googleRating")
                except:
                    googleRating = 0
                imagesArray = []
                imagesString = ""
                images = data.get('APP').get("gallery").get("items")
                for count in range(0, len(images)):
                    imagesString += images[count].get("galleryUrl") + "|"
                    imagesArray.append(images[count].get("galleryUrl"))

                post_fields = {"url": newUrl,"description": description,"make": make,"year": int(year),"model": model,"price": price.replace(",", ""),"trim": trim,"title": title,"mileage": mileage,"location": location,"mainCategory": mainCategory,"googleRating": googleRating,"imagesArray": imagesString,}
                    
                with open('dataJson'+str(filenameCount)+'.json', 'a') as out_file:
                    json.dump(post_fields, out_file, sort_keys=True, indent=4,
                              ensure_ascii=False)
                out_file.close()

        session.close()

    except:
        traceback.print_exc();

