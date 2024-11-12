import requests
import json
import sys

isAI = False

#La página php envia la ruta de la imagen a comprobar
image = sys.argv[1]

#Se establecen los parametros de la API
params = {
  'models': 'genai',
  'api_user': '434396958',
  'api_secret': 'GVvJisjJ9T8gySvBY7s6EbXUWrVruSH7'
}

#Se indica a la API la imagen a comprobar
files = {'media': open(image, 'rb')}

#Llamada a la API
r = requests.post('https://api.sightengine.com/1.0/check.json', files=files, params=params)

output = json.loads(r.text)

#Si la API tiene una confianza del 80% de que la imagen es IA, se considerá plagio
if output['type']['ai_generated'] > 0.80:
    isAI = True

print(isAI)
