        document.addEventListener('DOMContentLoaded', function() {
            const scheduleCells = document.querySelectorAll('.schedule-cell');
            
            let currentCell = null;

            // Schedule cell click handler
            scheduleCells.forEach(cell => {
                cell.addEventListener('click', function() {
                    currentCell = this;
                    const day = this.getAttribute('data-day');
                    const hour = this.getAttribute('data-hour');
                    
                    document.getElementById('selectedDay').value = day;
                    document.getElementById('selectedHour').value = hour;
                    
                    // If existing schedule, populate form
                    const existingLink = this.querySelector('.subject-link');
                    if (existingLink) {
                        const parts = existingLink.textContent.split('\n');
                        document.getElementById('subject').value = parts[0];
                        document.getElementById('teachingTime').value = parts[1] || '';
                        document.getElementById('classInput').value = parts[2] || '';
                    } else {
                        document.getElementById('subject').value = '';
                        document.getElementById('teachingTime').value = '';
                        document.getElementById('classInput').value = '';
                    }
                    
                    addScheduleModal.style.display = 'flex';
                });
            });

            // Button to open modal
            addScheduleBtn.addEventListener('click', function() {
                currentCell = null;
                
                document.getElementById('subject').value = '';
                document.getElementById('teachingTime').value = '';
                document.getElementById('classInput').value = '';
                
                document.getElementById('selectedDay').value = '';
                document.getElementById('selectedHour').value = '';
                
                addScheduleModal.style.display = 'flex';
            });

            // Close modal
            closeModal.addEventListener('click', function() {
                addScheduleModal.style.display = 'none';
            });

            // Form submission
            scheduleForm.addEventListener('submit', function(e) {
                e.preventDefault();
                
                const subject = document.getElementById('subject').value;
                const teachingTime = document.getElementById('teachingTime').value;
                const classInput = document.getElementById('classInput').value;
                
                if (!subject) {
                    alert('Mata pelajaran tidak boleh kosong');
                    return;
                }
                
                if (currentCell) {
                    // Update existing cell
                    updateScheduleCell(currentCell, subject, teachingTime, classInput);
                } else {
                    // Apply to all selected
                    const day = document.getElementById('selectedDay').value;
                    const hour = document.getElementById('selectedHour').value;
                    
                    if (!day) {
                        alert('Silakan pilih hari dan jam dahulu');
                        return;
                    }
                    
                    const cells = document.querySelectorAll(`.schedule-cell[data-day="${day}"][data-hour="${hour}"]`);
                    cells.forEach(cell => {
                        updateScheduleCell(cell, subject, teachingTime, classInput);
                    });
                }
                
                saveSchedule();
                addScheduleModal.style.display = 'none';
            });

            // Function to update schedule cell
            function updateScheduleCell(cell, subject, teachingTime, classInput) {
                // Clear existing content
                cell.innerHTML = '';
                
                // Create new link
                const link = document.createElement('a');
                link.className = 'subject-link';
                link.href = 'jadwalv2.html';
                link.innerHTML = `${subject}<br><small class="text-sm text-gray-500">${teachingTime}</small><br><small class="text-xs text-gray-400">${classInput}</small>`;
                
                // Allow clicking on the link without triggering cell click
                link.addEventListener('click', function(e) {
                    e.stopPropagation();
                });
                
                cell.appendChild(link);
            }

            // Function to save schedule to localStorage
            function saveSchedule() {
                const scheduleData = {};
                
                scheduleCells.forEach(cell => {
                    const day = cell.getAttribute('data-day');
                    const hour = cell.getAttribute('data-hour');
                    const link = cell.querySelector('.subject-link');
                    
                    if (!scheduleData[day]) {
                        scheduleData[day] = {};
                    }
                    
                    if (link) {
                        const parts = link.textContent.split('\n');
                        scheduleData[day][hour] = {
                            subject: parts[0],
                            teachingTime: parts[1] || '',
                            classInput: parts[2] || ''
                        };
                    } else {
                        scheduleData[day][hour] = null;
                    }
                });
                
                localStorage.setItem('schedule', JSON.stringify(scheduleData));
            }

            // Function to load schedule from localStorage
            function loadSchedule() {
                const scheduleData = JSON.parse(localStorage.getItem('schedule')) || {};
                
                scheduleCells.forEach(cell => {
                    const day = cell.getAttribute('data-day');
                    const hour = cell.getAttribute('data-hour');
                    
                    cell.innerHTML = '';
                    
                    if (scheduleData[day] && scheduleData[day][hour]) {
                        const data = scheduleData[day][hour];
                        updateScheduleCell(cell, data.subject, data.teachingTime, data.classInput);
                    }
                });
            }

            // Initialize sample data (optional)
            function initializeSampleData() {
                if (!localStorage.getItem('schedule')) {
                    const sampleSchedule = {
                        senin: {
                            '1': { subject: 'Matematika', teachingTime: '07:00', classInput: 'X Multimedia' },
                            '3': { subject: 'Desain Grafis', teachingTime: '09:00', classInput: 'X Multimedia' }
                        },
                        rabu: {
                            '2': { subject: 'Bahasa Inggris', teachingTime: '08:00', classInput: 'X Multimedia' }
                        }
                    };
                    localStorage.setItem('schedule', JSON.stringify(sampleSchedule));
                }
            }

            initializeSampleData();
        });