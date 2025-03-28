#include <iostream>
#include <sstream>
#include <vector>
#include <string>
#include <unordered_map>

std::vector<std::string> splitIntoWords(const std::string &text) {
    std::stringstream ss(text);
    std::string word;
    std::vector<std::string> words;
    while (ss >> word) {
        words.push_back(word);
    }
    return words;
}

std::string longestCommonSubstring(const std::vector<std::string> &text1Words, const std::vector<std::string> &text2Words) {
    size_t maxLength = 0;
    std::string longestSubstring;

    for (size_t i = 0; i < text1Words.size(); ++i) {
        for (size_t j = 0; j < text2Words.size(); ++j) {
            size_t length = 0;
            std::string currentSubstring;

            // Check for common words
            while (i + length < text1Words.size() && j + length < text2Words.size() && text1Words[i + length] == text2Words[j + length]) {
                if (length > 0) {
                    currentSubstring += " ";
                }
                currentSubstring += text1Words[i + length];
                length++;
            }

            if (length > maxLength) {
                maxLength = length;
                longestSubstring = currentSubstring;
            }
        }
    }
    return longestSubstring;
}

int main(int argc, char *argv[]) {
    if (argc != 3) {
        std::cerr << "Usage: " << argv[0] << " <text1> <text2>" << std::endl;
        return 1;
    }

    std::string text1 = argv[1];
    std::string text2 = argv[2];

    auto text1Words = splitIntoWords(text1);
    auto text2Words = splitIntoWords(text2);

    std::string result = longestCommonSubstring(text1Words, text2Words);
    std::cout << result << std::endl;

    return 0;
}
