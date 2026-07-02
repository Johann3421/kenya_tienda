s1 = r"'\"[{\\\"nombre\\\":\\\"\\\"}]\"'"
s2 = r"'[{\"titulo\":\"LA COMPUTADORA NO ENCIENDE\"}]'"

def clean(s):
    # Remove outer double quotes from double-encoded JSON
    s = s.replace('\'\\"', '\'')
    s = s.replace('\\"\'', '\'')
    # Unescape triple-escaped quotes (from double-encoded JSON)
    s = s.replace('\\\\\\"', '"')
    # Unescape regular escaped quotes (from standard JSON)
    s = s.replace('\\"', '"')
    return s

print("Row 24 (Double Encoded):")
print("Original:", s1)
print("Cleaned: ", clean(s1))
print()
print("Row 16 (Regular JSON):")
print("Original:", s2)
print("Cleaned: ", clean(s2))
