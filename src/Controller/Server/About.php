<?php
namespace Jeff\Code\Controller\Server;

use Jeff\Code\View\HeaderedContent;

class About extends HeaderedContent
{
	protected function getTitle(): string
	{
		return "About";
	}

	protected function content(): void
	{
		?>
            <div class="about-cont">
                <div class="about-text">
                    <p>The <strong>Brain Dribbler</strong> project is an experiment in full-stack edge computing, running on the following specs:</p>

                    <h4>Hardware & Operating System</h4>
                    <ul>
                        <li><a href="https://www.raspberrypi.com/" target="_blank">Raspberry Pi</a> - <i>Model 4</i></li>
                        <li><a href="https://ubuntu.com/" target="_blank">Ubuntu Linux</a> - <i>v24.04 LTS</i></li>
                    </ul>

                    <h4>Web Server & Proxy</h4>
                    <ul>
                        <li><a href="https://nginx.org/en/" target="_blank">Nginx</a> - <i>v1.24.0</i></li>
                    </ul>

                    <h4>DevOps & Language</h4>
                    <ul>
                        <li><a href="https://www.php.net/docs.php" target="_blank">PHP</a> - <i>v8.2.30</i></li>
                        <li><a href="https://github.com/copejj/job_log" target="_blank">GitHub Repository</a> - <i><a href="https://github.com/copejj/job_log/blob/main/.github/workflows/deploy.yml">Automated CI/CD</a></i></li>
                        <li><a href="https://gemini.google" target="_blank">Google Gemini</a> - <i>AI-Assisted Development & Optimization</i></li>
                    </ul>
                    
                    <h4>Database</h4>
                    <ul>
                        <li><a href="https://www.postgresql.org/docs/" target="_blank">PostgreSQL</a> Database - <i>v16.11</i></li>
                    </ul>
                </div>
                <div class="about-image">
                    <img src="/images/the_brain_dribbler.png" alt="The Brain Dribbler" />
                </div>
            </div>
		<?php
	}
}