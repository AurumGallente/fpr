import sys
import nltk
import pycountry
import json

from readability import Readability

def get_language_name_lowercase(code):
    try:
        # Use pycountry to look up the language by its ISO 639-1 code
        language = pycountry.languages.get(alpha_2=code)
        if language:
            return language.name.lower()  # Convert to lowercase
        else:
            return "english"
    except LookupError:
        return "language code not found."


text = sys.argv[1]
lang = sys.argv[2]

language = get_language_name_lowercase(lang)


r = Readability(text)

kincaid = r.flesch_kincaid()
f = r.flesch()
dc = r.dale_chall()
ari = r.ari()
cl = r.coleman_liau()
gf = r.gunning_fog()
#s = r.smog(all_sentences=True)
s = r.spache()
lw = r.linsear_write()

json_result = {

            "flesch_kincaid": {
                "score": kincaid.score,
                "grade_level": int(kincaid.grade_level)
            },
            "flesch": {
                "score": f.score,
                "ease": f.ease,
                "grade_levels": f.grade_levels
            },
            "dale": {
                "score": dc.score,
                "grade_levels": dc.grade_levels
            },
            "ari": {
                "score": ari.score,
                "grade_levels": ari.grade_levels,
                "ages": ari.ages
            },
            "coleman_liau": {
                "score": cl.score,
                "grade_level": int(cl.grade_level)
            },
            "gunning_fog": {
                "score": gf.score,
                "grade_level": gf.grade_level
            },
            "spache": {
                "score": s.score,
                "grade_level": int(s.grade_level)
            },
            "linsear_write": {
                "score": lw.score,
                "grade_level": int(lw.grade_level)
            }

}

print(json.dumps(json_result))
