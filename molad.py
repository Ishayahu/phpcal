from pyluach import dates, hebrewcal, parshios
import sys
import json
 #The month as an integer starting with 7 for Tishrei through 13 if necessary for Adar Sheni
# and then 1-6 for Nissan - Elul.
year = int(sys.argv[1])
month = int(sys.argv[2])
a = hebrewcal.Month(year, month)
cur_month = hebrewcal.Month(year, month-1)
#print(a)

result = a.molad_announcement()
last_day_of_month = [b for b in cur_month.iterdates()][-1].day
result['last_day_of_month'] = last_day_of_month
# добавляем число дней в ТЕКУЩЕМ месяце, это нужно чтобы понять, делать ли объявление
r = json.dumps(result)

print(r)
