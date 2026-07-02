s = r"'\"[{\\\"nombre\\\":\\\"\\\"}]\"'"
print("Original:", s)

# First, let's remove the wrapping \" at the beginning and end of the JSON array
s = s.replace('\'\\"', '\'')  # Replace '\" with '
s = s.replace('\\"\'', '\'')  # Replace \"' with '

# Now replace the inner escaped quotes
s = s.replace('\\\\\\"', '"')  # Replace \\\" with "

print("Cleaned:", s)
