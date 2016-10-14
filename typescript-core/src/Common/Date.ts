import {Dictionary} from "./Dictionary";
import {JsonSerializable} from "./JsonSerializable";
import {WrongArgumentException} from "./Exceptions";

export enum WeekDay {
    MONDAY 	    = 1,
    TUESDAY	    = 2,
    WEDNESDAY	= 3,
    THURSDAY	= 4,
    FRIDAY	    = 5,
    SATURDAY	= 6,
    SUNDAY	    = 0,
}

export const SECONDS_IN_DAY = 60 * 60 * 24;

function zeropad(num: number, length: number = 2) {
    var str: string = num.toString();
    while (str.length < length) {
        str = '0' + str;
    }
    return str;
}

const FORMATTERS: Dictionary<(Date) => string> = {
    'Y': (d: Date) => zeropad(d.getFullYear(), 4),
    'm': (d: Date) => zeropad(d.getMonth() + 1),
    'd': (d: Date) => zeropad(d.getDate()),
    'H': (d: Date) => zeropad(d.getHours()),
    'i': (d: Date) => zeropad(d.getMinutes()),
    's': (d: Date) => zeropad(d.getSeconds()),
};

export class DateOnly implements JsonSerializable {
    protected d: Date;

    public readonly FORMAT = 'Y-m-d';

    public static now() {
        return new this(new Date());
    }

    constructor(date: Date | number | string) {
        if (typeof date === 'string') {
            // 31.12.1999 => 1999-12-31
            let dateStr = date.replace(/^(\d{2})\.(\d{2})\.(\d{4})/, (_, d, m, Y) => [Y,m,d].join('-'));
            // 12/31/1999 => 1999-12-31
            dateStr =  dateStr.replace(/^(\d{2})\/(\d{2})\/(\d{4})/, (_, m, d, Y) => [Y,m,d].join('-'));

            this.d = new Date(dateStr);

            if (this.toString() != dateStr) {
                throw new WrongArgumentException('strange input given - ' + date);
            }

        } else if (typeof date === 'number') {
            this.d = new Date(Math.floor(date * 1000));

        } else if (date instanceof Date) {
            this.d = date;

        } else {
            throw new WrongArgumentException('strange input type given - ' + typeof date);
        }
    }

    public toString(format: string = this.FORMAT) : string {
        let out = '';
        for (let i: number = 0; i < format.length; i++) {
            let char: string = format[i];
            if (FORMATTERS.hasOwnProperty(char)) {
                let formatter = FORMATTERS[char];
                out += formatter(this.d);
            } else {
                out += char;
            }
        }
        return out;
    }

    public static dayDifference(older: DateOnly, newer: DateOnly) : number
    {
        return (newer.toStamp() - older.toStamp()) / SECONDS_IN_DAY;
    }

    public static compare(a: DateOnly, b: DateOnly) : number
    {
        if (a.toStamp() == b.toStamp()) {
            return 0;
        } else {
            return (a.toStamp() > b.toStamp() ? 1 : -1);
        }
    }

    public toJSON() : string {
        return this.toString();
    }

    public toStamp() : number {
        return Math.floor(this.d.getTime());
    }

    public toDate(delimiter = '-') : string {
        return this.toString(['Y', 'm', 'd'].join(delimiter));
    }

    public getYear() : string {
        return this.toString('Y');
    }

    public getMonth() : string  {
        return this.toString('m');
    }

    public getDay() : string {
        return this.toString('d');
    }

    public getWeekDay() : WeekDay {
        return this.d.getDay();
    }

    public getDayStartStamp() : number {
        return new DateOnly(this.toDate()).toStamp();
    }

    public getDayEndStamp() : number {
        return this.getDayStartStamp() + SECONDS_IN_DAY - 1;
    }

    public getFirstDayOfWeek(weekStart: WeekDay = WeekDay.MONDAY) : DateOnly {
        let dayDiff = (7 + this.getWeekDay() - weekStart) % 7;
        return new DateOnly(this.toStamp() - dayDiff * SECONDS_IN_DAY);
    }

    public getLastDayOfWeek(weekStart: WeekDay = WeekDay.MONDAY) : DateOnly {
        let dayDiff = (13 - this.getWeekDay() + weekStart) % 7;
        return new DateOnly(this.toStamp() + dayDiff * SECONDS_IN_DAY);
    }

    public toIsoString() {
        return this.d.toISOString();
    }

    public toDateTime() {
        return new DateTime(this.toStamp());
    }

    public getJsDate() {
        return this.d;
    }

    public addDays(days: number) {
        this.d.setTime(this.d.getTime() + days * SECONDS_IN_DAY * 1000);
        return this;
    }

}

export class DateTime extends DateOnly {
    public readonly FORMAT = 'Y-m-d H:i:s';

    public addHours(hours: number) {
        this.d.setTime(this.d.getTime() + hours * 60 * 60 * 1000);
        return this;
    }

    public addMinutes(mins: number) {
        this.d.setTime(this.d.getTime() + mins * 60 * 1000);
        return this;
    }

    public addSeconds(secs: number) {
        this.d.setTime(this.d.getTime() + secs * 1000);
        return this;
    }

}
