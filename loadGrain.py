
def set_bit(value, bit):
    return value | (1<<bit)

# Початковий код
def loadGrain(levels:list, check:int):
    # // your code here
    if len(levels) <= 2:
        return 0

    width = len(levels)
    height = max(levels)
    bottom = min(levels)

    totalCell = width*height

    buffer = list()
    grain_counter= list()
    grain_buffer =list() 

    for n, l in zip(range(width), levels):
        grain_buffer.append(l)
        allow_increase = False
        if n+1 < width:
            allow_increase = True 

        if allow_increase and levels[n+1] < grain_buffer[0]:
            pass
        else:
            ##if n+1 == width and max(grain_buffer[1:]):
            ## todo fix last case one more
            ## check for last items that less of buffer head
            ## some test are buggy
            head = grain_buffer[:1]
            if (len(head)):
                a = max(grain_buffer) * len(grain_buffer)
                b = sum(grain_buffer)
                grain_counter.append(a-b)
            
            grain_buffer.clear()
        print()
    print(sum(grain_counter))

# Приклади тестів

#loadGrain([4, 1, 3], 2) #// 2
loadGrain([2, 1, 5, 2, 7, 4, 10], 7) #// 7
#loadGrain([2, 0, 1, 5, 2, 7], 6) #// 6
#loadGrain([2, 0, 1, 5, 2, 7, 2, 0, 1, 5, 2, 7], 6) #// 6
#loadGrain([2, 0, 1, 5, 2, 17, 2, 0, 1, 5, 2, 7, 12, 0, 1, 5, 2, 7], 6) #// 6
#loadGrain([2, 4, 2], 0) #// 0
#loadGrain([7, 4], 0) #// 0
#loadGrain([], 0) #// 0
#loadGrain([2, 0, 0, 2], 4) #// 0
#loadGrain([0, 2, 0, 2], 4) #// 0
#loadGrain([2, 0, 0, 1], 2) #// 0
#loadGrain([3, 0, 0, 3], 6) #// 0
#loadGrain([3, 0, 5, 1, 2], 6) #// 0 
#loadGrain([3, 0, 0, 3, 1, 2], 7) #// 0 -

