import json
s = r"'\"[{\\\"nombre\\\":\\\"\\\",\\\"serie\\\":\\\"\\\",\\\"falla\\\":\\\"\\\"}]\"'"
print("Original:", s)
s2 = s.replace('\\"', '"')
print("After first replace:", s2)
s3 = s2.replace('\\"', '"')
print("After second replace:", s3)
