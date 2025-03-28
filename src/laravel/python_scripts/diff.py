import sys
import json

def longest_substring(text1, text2):
    words1 = text1.split()
    words2 = text2.split()

    # Create a 2D list to store lengths of longest common suffixes
    lengths = [[0] * (len(words2) + 1) for _ in range(len(words1) + 1)]
    max_length = 0
    end_index = 0

    # Build the lengths table
    for i in range(1, len(words1) + 1):
        for j in range(1, len(words2) + 1):
            if words1[i - 1] == words2[j - 1]:
                lengths[i][j] = lengths[i - 1][j - 1] + 1
                if lengths[i][j] > max_length:
                    max_length = lengths[i][j]
                    end_index = i
            else:
                lengths[i][j] = 0

    # Retrieve the longest substring
    longest_substr = ' '.join(words1[end_index - max_length:end_index])
    return longest_substr

if __name__ == "__main__":
    if len(sys.argv) != 3:
        print("Usage: python longest_substring.py <text1> <text2>")
        sys.exit(1)

    text1 = sys.argv[1]
    text2 = sys.argv[2]

    result = longest_substring(text1, text2)

    # Create a JSON response
    response = {
        "longest_common_sequence": result
    }

    # Print the JSON response
    print(json.dumps(response))
