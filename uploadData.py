import json
from urllib.parse import urlencode
from urllib.request import urlopen, Request

import requests

for abc in range(1,52):
    f = open("dataJson"+str(abc)+".json","r").read()

    data = f.replace("}{", "},{")
    finlData = '{"data":[' + data + ']}'

    countt=0
    stud_obj = json.loads(finlData)
    for obj in stud_obj.get("data"):
        # print(obj.get('title'))
        # urladas = 'http://localhost/wordpress/upload.php'
        urladas = 'https://lemonorchariot.com/upload.php'
        post_fields = {
            "url": obj.get('url'),
            "description": obj.get('description'),
            "make": obj.get('make'),
            "year": obj.get('year'),
            "model": obj.get('model'),
            "price": obj.get('price').replace(",", ""),
            "trim": obj.get('trim'),
            "title": obj.get('title'),
            "mileage": obj.get('mileage'),
            "location": obj.get('location'),
            "mainCategory": obj.get('mainCategory'),
            "googleRating": obj.get('googleRating'),
            "imagesArray": obj.get('imagesArray'),
        }
        countt+=1
        print(countt)
        # request = Request(url, urlencode(post_fields).encode())
        request = requests.post(url=urladas, data=post_fields)

        print(request.text)
