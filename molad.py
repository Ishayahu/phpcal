from pyluach import dates, hebrewcal, parshios
import json
 #The month as an integer starting with 7 for Tishrei through 13 if necessary for Adar Sheni
# and then 1-6 for Nissan - Elul.
a = hebrewcal.Month(5782, 6)
r = json.dumps(a.molad_announcement())
print(r)
