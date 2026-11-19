"""Консольний клієнт публічного API Ігротеки."""

import argparse
import json
import sys
from urllib.error import HTTPError, URLError
from urllib.request import Request, urlopen


def main():
    parser = argparse.ArgumentParser(description=__doc__)
    parser.add_argument("id", nargs="?", type=int, help="номер гри для повного опису")
    parser.add_argument("--base-url", default="http://127.0.0.1:8000",
                        help="адреса сервісу")
    args = parser.parse_args()
    if args.id is not None and args.id < 1:
        parser.error("номер гри має бути додатним")

    url = args.base_url.rstrip("/") + "/api/games"
    if args.id is not None:
        url += f"/{args.id}"

    try:
        request = Request(url, headers={"Accept": "application/json"})
        with urlopen(request, timeout=10) as response:
            data = json.load(response)
    except HTTPError as error:
        message = "Гру не знайдено" if error.code == 404 else error.reason
        print(f"HTTP {error.code}: {message}", file=sys.stderr)
        return 1
    except (URLError, OSError, ValueError) as error:
        print(f"Не вдалося прочитати API: {error}", file=sys.stderr)
        return 1

    games = data if args.id is None else [data]
    if not games:
        print("У каталозі поки немає ігор.")
    for game in games:
        low, high = game["minPlayers"], game["maxPlayers"]
        players = str(low) if low == high else f"{low}–{high}"
        print(f'#{game["id"]}  {game["title"]} | {game["category"]} | '
              f'гравці: {players} | {game["minutes"]} хв')
        if args.id is not None:
            print(game["description"])
    return 0


if __name__ == "__main__":
    sys.exit(main())
