import copy
from fontTools.ttLib import TTFont
from PIL import Image, ImageDraw, ImageFont

# Cargamos Orbitron-Black que tiene todos los glifos de alta calidad
orbitron = TTFont('public/TIPOGRAFIA/Orbitron-Black.ttf')

# Creamos una copia para RaitoFont
raito_font = TTFont('public/TIPOGRAFIA/Orbitron-Black.ttf')

# Corregimos el glifo 'i' minúscula para que tenga un punto bien separado y nunca parezca 'l'
glyf = raito_font['glyf']
gi = glyf['i']

# Coordenadas originales de i en Orbitron:
# cuerpo: (52, 0), (52, 580), (205, 580), (205, 0)
# punto: (52, 770), (205, 770), (205, 616), (52, 616)
# El gap original es 616 - 580 = 36 unidades (muy pequeño).
# Ajustamos cuerpo hasta 530 y punto de 630 a 770 (gap de 100 unidades visible):
new_i_coords = [
    (52, 0), (52, 530), (205, 530), (205, 0), # cuerpo
    (52, 770), (205, 770), (205, 630), (52, 630) # punto
]
for idx, pt in enumerate(new_i_coords):
    gi.coordinates[idx] = pt

gi.yMin = 0
gi.yMax = 770

# Mapeo cmap: asegurar que R, A, I, T, O y r, a, i, t, o estén correctamente direccionados
# Mayúsculas: R, A, I, T, O
# Minúsculas: r, a, i, t, o (con la i corregida)
cmap = raito_font.getBestCmap()

# Guardamos la nueva fuente en TTF y OTF
raito_font.save('scratch/Raito-Regular.ttf')
raito_font.save('scratch/Raito-Regular.otf')

# Probamos renderizar ambas variantes
img = Image.new('RGB', (600, 240), color=(17, 17, 17))
draw = ImageDraw.Draw(img)

f = ImageFont.truetype('scratch/Raito-Regular.ttf', 55)
draw.text((40, 25), 'RAITO (Mayúsculas):', fill=(150, 150, 150))
draw.text((40, 60), 'RAITO', font=f, fill=(255, 255, 255))

draw.text((40, 130), 'Raito (Minúsculas corregida):', fill=(150, 150, 150))
draw.text((40, 165), 'Raito', font=f, fill=(255, 255, 255))

img.save('scratch/raito_new_font_test.png')
print('Fuente generada y probada con éxito')
