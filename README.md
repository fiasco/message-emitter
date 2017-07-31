# message-emitter
Emits stub data messages to use for testing purposes.

## Installation

```
composer install
```

## Usage

```
./stub.php emit --size=4 --rate=10 --vary-size=0.05 --start-time='+10 seconds' --limit=5
```
The above example emits a ~4kb message every ~6 seconds (10msg/min). The message size is varied in each request by 5% each way.
Valid messages are emitted after 10 seconds into the runtime. 5 valid messages will be emitted before exiting.
