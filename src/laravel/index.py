import sys
import nltk
import json

#nltk.download('punkt_tab')


from readability import Readability


text = "Italy, country of south-central Europe, occupying a peninsula that juts deep into the Mediterranean Sea. Italy comprises some of the most varied and scenic landscapes on Earth and is often described as a country shaped like a boot. At its broad top stand the Alps, which are among the world’s most rugged mountains. Italy’s highest points are along Monte Rosa, which peaks in Switzerland, and along Mont Blanc, which peaks in France. The western Alps overlook a landscape of Alpine lakes and glacier-carved valleys that stretch down to the Po River and the Piedmont. Tuscany, to the south of the cisalpine region, is perhaps the country’s best-known region. From the central Alps, running down the length of the country, radiates the tall Apennine Range, which widens near Rome to cover nearly the entire width of the Italian peninsula. South of Rome the Apennines narrow and are flanked by two wide coastal plains, one facing the Tyrrhenian Sea and the other the Adriatic Sea. Much of the lower Apennine chain is near-wilderness, hosting a wide range of species rarely seen elsewhere in western Europe, such as wild boars, wolves, asps, and bears. The southern Apennines are also tectonically unstable, with several active volcanoes, including Vesuvius, which from time to time belches ash and steam into the air above Naples and its island-strewn bay. At the bottom of the country, in the Mediterranean Sea, lie the islands of Sicily and Sardinia. Italy’s political geography has been conditioned by this rugged landscape. With few direct roads between them, and with passage from one point to another traditionally difficult, Italy’s towns and cities have a history of self-sufficiency, independence, and mutual mistrust. Visitors today remark on how unlike one town is from the next, on the marked differences in cuisine and dialect, and on the many subtle divergences that make Italy seem less a single nation than a collection of culturally related points in an uncommonly pleasing setting."

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
    'flesch': {
        'score': kincaid.score,
        'grade_level': int(kincaid.grade_level)
    },
    'flesch': {
        'score': f.score,
        'ease': f.ease,
        'grade_levels': f.grade_levels
    },
    'dale': {
        'score': dc.score,
        'grade_levels': dc.grade_levels
    },
    'ari': {
        'score': ari.score,
        'grade_levels': ari.grade_levels,
        'ages': ari.ages
    },
    'coleman_liau': {
        'score': cl.score,
        'grade_level': int(cl.grade_level)
    },
    'gunning_fog': {
        'score': gf.score,
        'grade_level': gf.grade_level
    },
    'spache': {
        'score': s.score,
        'grade_level': int(s.grade_level)
    },
    'linsear_write': {
        'score': lw.score,
        'grade_level': int(lw.grade_level)
    }

}

print(json_result)
