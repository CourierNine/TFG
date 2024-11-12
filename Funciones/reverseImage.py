import requests
import json
import sys
import base64

#La página php envia la ruta de la imagen a comprobar
image = sys.argv[1]
isGoogle = False

#Parametros de la API
api_key = 'AIzaSyCZMmqptCfAlxW41QoxP9DJQJS_oCVZCug'
url = f'https://vision.googleapis.com/v1/images:annotate?key={api_key}'

#Se abre la imagen a comprobar
with open(image, 'rb') as image_open:
    encoded_image = base64.b64encode(image_open.read()).decode('utf-8')

#Se indica a la API la imagen
payload = {
    'requests': [
        {
            'image': {
                'content': encoded_image
            },
            'features': [
                {
                    'type': 'WEB_DETECTION',
                    'maxResults': 10
                }
            ]
        }
    ]
}

#Llamada a la API
response = requests.post(url, data=json.dumps(payload), headers={'Content-Type': 'application/json'})

response_data = response.json()

#Se comprueban los resultados obtenidos por la API y si la API cree que
#ha encontrado la misma imagen (con más de un 80% de probabilidad) se considerá
#plagio
if 'responses' in response_data and len(response_data['responses']) > 0:
    web_detection = response_data['responses'][0].get('webDetection', {})

    if 'webEntities' in web_detection:
            for entity in web_detection['webEntities']:
                description = entity.get('description', 'No description')
                score = entity.get('score', 'No score')


                if score > 0.80:
                    isGoogle = True

    else:
        print("No web entities found.")

else:
    print("No web detection data found in the response.")

print(isGoogle)
