from PIL import Image, ImageDraw, ImageFont

crop_user = Image.open('scratch/user_crop_ralto_exact.png')

comp = Image.new('RGB', (650, 260), color=(17, 17, 17))
d = ImageDraw.Draw(comp)

d.text((20, 15), '1. Captura real de tu pantalla (Fuente original, se lee Ralto):', fill=(255, 100, 100))
comp.paste(crop_user, (20, 35))

f_fixed = ImageFont.truetype('scratch/test_font_3.ttf', 44)
d.text((20, 135), '2. Misma fuente Raito pero con separación visible en la i (se lee Raito):', fill=(100, 255, 100))
d.text((25, 165), 'Raito', font=f_fixed, fill=(255, 255, 255))

comp.save('scratch/compare_exact_screens.png')
print('Saved scratch/compare_exact_screens.png')
