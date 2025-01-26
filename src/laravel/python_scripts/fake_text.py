import sys
import random
from faker import Faker

def generate_long_text(locale='en_US', num_paragraphs=5):
    fake = Faker(locale)
    long_text = ' '.join([fake.paragraph() for _ in range(num_paragraphs)])  # Join multiple paragraphs
    return long_text

lang = sys.argv[1]
n = random.randint(10, 200)
long_text = generate_long_text(lang, n)

print(long_text)
