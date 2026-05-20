import requests, re, json

# Descargar el JS bundle del auditor
resp = requests.get('https://auditor.sekaitech.com.pe/assets/index-C9XHLeQ8.js', timeout=30)
js = resp.text

# Buscar endpoints de la API (Ho = instancia axios con baseURL api-auditor)
print("=== Endpoints de API del auditor ===")
matches = re.findall(r'Ho\.(get|post|put|delete)\((["\x60][^\x60"]+["\x60])', js)
for method, url in matches[:50]:
    print(f"  {method.upper():6} {url}")

# Buscar también con comillas simples
matches2 = re.findall(r"Ho\.(get|post|put|delete)\('([^']+)'", js)
for method, url in matches2[:50]:
    print(f"  {method.upper():6} {url}")

# Buscar palabras clave cerca de 'fichas'
print("\n=== Contexto de 'fichas' en el JS ===")
for m in re.finditer(r'fichas', js, re.IGNORECASE):
    ctx = js[max(0,m.start()-80):m.start()+120]
    print(f"  ...{ctx}...")
    print()

# Probar la API directamente
print("\n=== Probando api-auditor endpoints ===")
base = "https://api-auditor.sekaitech.com.pe/api/v1"
for path in ["/fichas", "/fichas-catalogo", "/fichas/catalogo", "/productos/fichas",
             "/fichas?marca=Kenya&page=1", "/catalogo?marca=Kenya"]:
    try:
        r = requests.get(base + path, timeout=10, headers={"Accept": "application/json"})
        print(f"  GET {path} → {r.status_code} ({len(r.content)} bytes) {r.headers.get('content-type','')[:50]}")
        if r.status_code == 200 and 'json' in r.headers.get('content-type',''):
            print(f"    {str(r.json())[:200]}")
    except Exception as e:
        print(f"  GET {path} → ERROR: {e}")
