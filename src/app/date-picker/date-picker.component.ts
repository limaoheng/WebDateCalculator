import { Component, OnInit } from '@angular/core';
import { DateService } from '../services/date.service';
import {NgbModule, NgbDateStruct} from '@ng-bootstrap/ng-bootstrap';
import { log } from 'util';

@Component({
  selector: 'app-date-picker',
  templateUrl: './date-picker.component.html',
  styleUrls: ['./date-picker.component.css']
})
export class DatePickerComponent {
  isLoading: boolean;

  public startDate;
  public startTimeZone;
  public startDateStr;

  public endDate;
  public endTimeZone;
  public endDateStr;

  public intervalDays;
  public intervalWeeks;
  public intervalWeekdays;
  
  public supportedTimeZones = [];
  
  public errorMessage;

  constructor(private dateService: DateService) { 
    // Get a supported time zone list
    dateService.getSupportedTimeZones().subscribe((data) => {
      this.supportedTimeZones = data['supportedTimeZones'];
    }, (error) => {
      console.log('Error! ', error);
    });
  }
  
  public setStartTimeZone(timeZone: string) {
    if (this.supportedTimeZones.includes(timeZone)) {
      this.startTimeZone = timeZone;
    }
  }
  
  public setEndTimeZone(timezone: string) {
    if (this.supportedTimeZones.includes(timezone)) {
      this.endTimeZone = timezone;
    }
  }

  onClick(event) {
    
    // Clear any error messages.
    if (this.errorMessage) {
      this.errorMessage = '';
    }
    
    const startDateStr = this.startDate.year + '-' +
      (this.startDate.month > 9 ? this.startDate.month : '0' + this.startDate.month) + '-' +
      (this.startDate.day > 9 ? this.startDate.day : '0' + this.startDate.day);
    this.startDateStr = startDateStr;

    const endDateStr = this.endDate.year + '-' +
      (this.endDate.month > 9 ? this.endDate.month : '0' + this.endDate.month) + '-' +
      (this.endDate.day > 9 ? this.endDate.day : '0' + this.endDate.day);
    this.endDateStr = endDateStr;

    // Before sending, check the Time Zone is correct.
    let startTZ;
    if (this.supportedTimeZones.includes(this.startTimeZone)) {
      startTZ = this.startTimeZone;
    }

    let endTZ;
    if (this.supportedTimeZones.includes(this.endTimeZone)) {
      endTZ = this.endTimeZone;
    }
    this.isLoading = true;
    this.dateService.calculateDate(startDateStr, endDateStr, startTZ, endTZ).subscribe((data) => {
        if (data['error']) {
          this.errorMessage = data['error']['msg'];
        } else {
          this.intervalDays   = data['days'];
          this.intervalWeeks  = data['weeks'];
          this.intervalWeekdays = data['weekdays'];
        }
        this.isLoading = false;
      }, (error) => {
        console.log('Error! ', error);
        this.isLoading = false;
      });

  }

}
