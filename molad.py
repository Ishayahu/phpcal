from pyluach import dates, hebrewcal, parshios
import sys
import json
 #The month as an integer starting with 7 for Tishrei through 13 if necessary for Adar Sheni
# and then 1-6 for Nissan - Elul.
year = int(sys.argv[1])
month = int(sys.argv[2])
a = hebrewcal.Month(year, month)
#print(a)
r = json.dumps(a.molad_announcement())
print(r)
