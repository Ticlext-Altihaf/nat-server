# Nat Wave

## CPanel Setup

1. Clone with Git
2. Add `.htaccess` with permissions 644 to the root project folder
3. Upload `vendor` folder
4. Set `.env`
5. Set project folder permissions to 755

## Setup Device & Sensors

- Open `.env`
- `DEVICES_NAME` is the name of the device
- `DEVICES_SENSORS` is the list of sensors
- `DEVICES_BATTERY_SENSORS` is the list of sensors that will be displayed as battery
- Entity must be prefixed by `sensor.`

Example if you have 3 entities:

- `sensor.natwave01_td`
- `sensor.natwave02_ph`
- `sensor.natwave03_td`

the resulting `.env` will be:

```env
DEVICES_NAME="natwave01 natwave02 natwave03"
DEVICES_SENSORS="td ph"
```

## Final Score Matrix

| PH\ORP | 🟩 | 🟨 | 🟥 |
|--------|----|----|----|
| 🟩     | 🟩 | 🟨 | 🟥 |
| 🟨     | 🟨 | 🟥 | 🟥 |
| 🟥     | 🟥 | 🟥 | 🟥 |

- 🟩 = 1 - 0.7
- 🟨 = 0.7 - 0.4
- 🟥 = 0.4 - 0

Mathematical formula:

```
orpScore * phScore
```


