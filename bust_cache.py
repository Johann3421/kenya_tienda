import os
import re

views_dir = 'resources/views'

def bust_cache():
    for root, dirs, files in os.walk(views_dir):
        for file in files:
            if file.endswith('.blade.php'):
                filepath = os.path.join(root, file)
                with open(filepath, 'r', encoding='utf-8') as f:
                    content = f.read()

                # Find all occurrences of baner*.png inside asset(...) or url(...)
                # E.g. 'banercatalogo.png' or "banercatalogo.png"
                # Let's just blindly replace .png') or .png") with .png?v=2') or .png?v=2") 
                # but only for these specific banners
                
                banners = [
                    'banercontacto.png',
                    'banersoporte.png',
                    'banernovedades.png',
                    'banercatalogo.png',
                    'banersomos.png'
                ]
                
                updated = False
                for banner in banners:
                    if banner in content and banner + '?v=2' not in content:
                        content = content.replace(banner, banner + '?v=2')
                        updated = True

                if updated:
                    with open(filepath, 'w', encoding='utf-8') as f:
                        f.write(content)
                    print(f"Updated {filepath}")

bust_cache()
