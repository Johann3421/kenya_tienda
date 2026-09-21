from PIL import Image, ImageDraw, ImageFont

f_ezent = ImageFont.truetype('public/TIPOGRAFIA KENYA/EZENT/Ezent-Regular.ttf', 44)
f_genwork = ImageFont.truetype('public/TIPOGRAFIA KENYA/GENWORK/Genwork-Regular.ttf', 44)
f_ofiszu = ImageFont.truetype('public/TIPOGRAFIA KENYA/OFISZU Y HENKO/OfiszuYHenko-Regular.ttf', 44)
f_prowork = ImageFont.truetype('public/TIPOGRAFIA KENYA/PROWORK/Prowork-Regular.ttf', 44)
f_orbitron = ImageFont.truetype('public/TIPOGRAFIA/Orbitron-Black.ttf', 44)
f_raito_curr = ImageFont.truetype('public/TIPOGRAFIA KENYA/RAITO/Raito-Regular.ttf', 44)
f_raito_fixed = ImageFont.truetype('scratch/test_font_3.ttf', 44)

img = Image.new('RGB', (920, 580), color=(18, 18, 18))
draw = ImageDraw.Draw(img)

draw.text((30, 20), 'MARCAS KENYA (todas en MAYUSCULAS):', fill=(180, 180, 180))

brands = [
    ('EZENT', f_ezent),
    ('GENWORK', f_genwork),
    ('OFISZU', f_ofiszu),
    ('PROWORK', f_prowork),
]

y = 60
for name, f in brands:
    draw.text((40, y), name, font=f, fill=(255, 255, 255))
    y += 55

draw.line([(30, y), (890, y)], fill=(60, 60, 60), width=1)
y += 20

draw.text((30, y), 'COMPARACION DE RAITO:', fill=(180, 180, 180))
y += 35

draw.text((40, y), 'Actual (en BD dice RAITO pero fuente emite minúsculas -> Ralto):', fill=(255, 100, 100))
draw.text((40, y + 25), 'RAITO', font=f_raito_curr, fill=(255, 255, 255))
y += 90

draw.text((40, y), 'Solución 1 - MAYÚSCULAS REALES (Consistente con todas las marcas):', fill=(100, 255, 100))
draw.text((40, y + 25), 'RAITO', font=f_orbitron, fill=(255, 255, 255))
y += 90

draw.text((40, y), 'Solución 2 - Minúsculas con glifo "i" corregido (punto cuadrado separado):', fill=(100, 200, 255))
draw.text((40, y + 25), 'Raito', font=f_raito_fixed, fill=(255, 255, 255))

img.save('scratch/raito_solutions_comparison.png')
print('Saved scratch/raito_solutions_comparison.png')
