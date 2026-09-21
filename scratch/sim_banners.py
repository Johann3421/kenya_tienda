from PIL import Image, ImageDraw, ImageFont

bg_path = 'scratch/banner_raito_bg.jpg'
bg = Image.open(bg_path).convert('RGB')
w, h = 1200, 450
bg = bg.resize((w, h))

def render_banner(title, font_path, out_path, is_caps=True):
    b = bg.copy()
    d = ImageDraw.Draw(b)
    f_title = ImageFont.truetype(font_path, 52)
    
    # Texto secundario
    d.text((150, 110), title, font=f_title, fill=(255, 255, 255))
    d.text((150, 180), 'MONITOR EMPRESARIAL', fill=(255, 255, 255))
    d.text((150, 215), 'Diseñada para entornos profesionales, oficinas y usuarios que buscan', fill=(210, 210, 210))
    d.text((150, 235), 'máxima calidad visual, comodidad y productividad.', fill=(210, 210, 210))
    
    # Boton naranja
    d.rounded_rectangle([(150, 280), (280, 325)], radius=20, fill=(245, 120, 10))
    d.text((170, 295), 'Ver Catálogo', fill=(255, 255, 255))
    
    b.save(out_path)

render_banner('RAITO', 'public/TIPOGRAFIA KENYA/RAITO/Raito-Regular.ttf', 'scratch/sim_actual.png')
render_banner('RAITO', 'public/TIPOGRAFIA/Orbitron-Black.ttf', 'scratch/sim_mayusculas.png')
render_banner('Raito', 'scratch/test_font_3.ttf', 'scratch/sim_minusculas.png')

print('Simulaciones generadas')
