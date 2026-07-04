import re

with open('resources/views/welcome.blade.php', 'r', encoding='utf-8') as f:
    content = f.read()

content = re.sub(
    r'#main-welcome-container \.brand-ezent \.producto-titulo,\s*#main-welcome-container \.brand-ezent \.hero-slide\s*\{\s*font-family:\s*\'EzentFont\',\s*sans-serif\s*!important;\s*\}',
    '#main-welcome-container .brand-ezent .producto-titulo,\n        #main-welcome-container .brand-ezent .hero-slide h1 {\n            font-family: \'United Kingdom\', sans-serif !important;\n            font-weight: 900 !important;\n        }',
    content
)

content = re.sub(
    r'#main-welcome-container \.brand-genwork \.producto-titulo,\s*#main-welcome-container \.brand-genwork \.hero-slide\s*\{\s*font-family:\s*\'GenworkFont\',\s*sans-serif\s*!important;\s*\}',
    '#main-welcome-container .brand-genwork .producto-titulo,\n        #main-welcome-container .brand-genwork .hero-slide h1 {\n            font-family: \'United Kingdom\', sans-serif !important;\n        }',
    content
)

content = re.sub(
    r'#main-welcome-container \.brand-ofiszu \.producto-titulo,\s*#main-welcome-container \.brand-ofiszu \.hero-slide\s*\{\s*font-family:\s*\'OfiszuFont\',\s*sans-serif\s*!important;\s*\}',
    '#main-welcome-container .brand-ofiszu .producto-titulo,\n        #main-welcome-container .brand-ofiszu .hero-slide h1 {\n            font-family: \'Tourmaline\', sans-serif !important;\n        }',
    content
)

content = re.sub(
    r'#main-welcome-container \.brand-prowork \.producto-titulo,\s*#main-welcome-container \.brand-prowork \.hero-slide\s*\{\s*font-family:\s*\'ProworkFont\',\s*sans-serif\s*!important;\s*\}',
    '#main-welcome-container .brand-prowork .producto-titulo,\n        #main-welcome-container .brand-prowork .hero-slide h1 {\n            font-family: \'Orbitron\', sans-serif !important;\n        }',
    content
)

content = re.sub(
    r'#main-welcome-container \.brand-raito \.producto-titulo,\s*#main-welcome-container \.brand-raito \.hero-slide\s*\{\s*font-family:\s*\'RaitoFont\',\s*sans-serif\s*!important;\s*\}',
    '#main-welcome-container .brand-raito .producto-titulo,\n        #main-welcome-container .brand-raito .hero-slide h1 {\n            font-family: \'Orbitron\', sans-serif !important;\n        }',
    content
)

with open('resources/views/welcome.blade.php', 'w', encoding='utf-8') as f:
    f.write(content)
