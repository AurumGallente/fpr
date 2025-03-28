import sys
import json
import re
from collections import Counter

def split_words(text):
    return text.split()

def find_longest_common_sequence(words1, words2):
    longest_common = ""
    min_length = 4

    for length in range(min_length, min(len(words1), len(words2)) + 1):
        words_count = Counter()

        for i in range(len(words1) - length + 1):
            common_candidate = ' '.join(words1[i:i + length])
            words_count[common_candidate] += 0  # Add key with initial count

        for i in range(len(words2) - length + 1):
            common_candidate = ' '.join(words2[i:i + length])
            if common_candidate in words_count:
                # Return the longest common sequence found
                if len(common_candidate) > len(longest_common):
                    longest_common = common_candidate

    return longest_common

def find_longest_common_sequence_only(text1, text2):
    words1 = split_words(text1)
    words2 = split_words(text2)

    longest_common_sequence = find_longest_common_sequence(words1, words2)

    # Remove the longest common sequence from both texts
    if longest_common_sequence:
        text1 = re.sub(re.escape(longest_common_sequence), "", text1)
        text2 = re.sub(re.escape(longest_common_sequence), "", text2)

    return longest_common_sequence

if __name__ == "__main__":
    # Check if the correct number of arguments are provided
    if len(sys.argv) != 3:
        print(json.dumps({"error": "Please provide exactly two text arguments."}))
        sys.exit(1)

    text1 = sys.argv[1]
    text2 = sys.argv[2]

    # Find the longest common sequence
    longest_common_sequence = find_longest_common_sequence_only(text1, text2)

    # Create a JSON response
    response = {
        "longest_common_sequence": longest_common_sequence
    }

    # Print the JSON response
    print(json.dumps(response))
