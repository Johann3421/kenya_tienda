import copy
from fontTools.ttLib import TTFont

# Cargamos la fuente original de 2048 bytes de Calligraphr
font_orig = TTFont('scratch/original_raito_clean.ttf')

glyf = font_orig['glyf']
gi = glyf['i']

# Coordenadas originales de i:
# endPtsOfContours: [3, 7]
# (50, 570), (50, 732), (208, 732), (208, 570) -> punto (alto 162, y: 570..732)
# (50, -48), (50, 539), (208, 539), (208, -48) -> cuerpo (y: -48..539)
# Separación original: 570 - 539 = 31 unidades (menos de 2px a escala web).
#
# Ajuste quirúrgico:
# Mantenemos cuerpo hasta 520 (misma altura que 'a' y 'o' que van a ~535-546)
# Movemos el punto cuadrado a 620..740 (separación de 100 unidades visible a cualquier tamaño):
gi.coordinates[0] = (50, 620)
gi.coordinates[1] = (50, 740)
gi.coordinates[2] = (208, 740)
gi.coordinates[3] = (208, 620)

gi.coordinates[4] = (50, -48)
gi.coordinates[5] = (50, 520)
gi.coordinates[6] = (208, 520)
gi.coordinates[7] = (208, -48)

gi.yMin = -48
gi.yMax = 740

# Guardamos en las rutas del proyecto
font_orig.save('public/TIPOGRAFIA KENYA/RAITO/Raito-Regular.ttf')
font_orig.save('public/TIPOGRAFIA KENYA/RAITO/Raito-Regular.otf')

print('Fuente Raito original actualizada quirúrgicamente con separación de punto en i')
